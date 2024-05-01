# Laravel JSON Meta

A library for saving metadata in a JSON column, with strict type checking using PHPStan.

* Best for saving configuration options and other metadata
* Nested objects are not supported

## Installation

Install the package via composer.

```bash
composer require hyvor/laravel-json-meta
```

If you use PHPStan (highly recommended), add the following to your `phpstan.neon` file. This improves the type checking of the library.

```neon
includes:
    - vendor/hyvor/laravel-json-meta/extension.neon
```


### Metadata or column?

When saving data, you have to decide between meta vs column.  A general rule is that if that data is needed for a `WHERE` or `ORDER BY`, save it in a column. If not, you may save it in metadata. In most cases, configuration options are the best things to save in metadata.

(You can still use metadata in `WHERE` queries if your database engine supports JSON operations.)

## Usage

Let's say you want to save metadata of `blogs` in a JSON column named `meta`.

First, add a JSON `meta` column to the table.

```php
Schema::create('blogs', function (Blueprint $table) {
    // other columns
    
    $table->json('meta')->nullable();
});
```

> If your database does not support native JSON columns, Laravel will create a TEXT column, which works fine with this library.


### Update the Model

* Add `HasMeta` trait
* Declare the `defineMeta` method

```php
use Hyvor\JsonMeta\HasMeta;
use Hyvor\JsonMeta\MetaDefinition;

class Blog extends Model
{
    
    use HasMeta;
    
    protected function defineMeta(MetaDefinition $meta) 
    {
        // definition goes here
    }

}
```

### Definition

"Definition" is which data types you allow saving in the `meta` field. By defining them, you can make sure that incorrect data is never inserted by invalid user input or typos in your code. The following methods are available in the `MetaDefinition` class.

- `string(string $name)`
- `integer(string $name)`
- `float(string $name)`
- `boolean(string $name)`
- `enum(string $name, string[]|class-string $values)`

```php
protected function defineMeta(MetaDefinition $meta)
{

    // string
    $meta->string('seo_robots')->nullable();
    
    // integer
    $meta->integer('max_comments')->default(100);
    
    // float
    $meta->float('comment_delay')->default(0.5);
    
    // boolean
    $meta->boolean('seo_indexing')->default(true);
    
    // enum (string)
    $meta->enum('comments_type', ['hyvor', 'other'])->default('hyvor');
    
    // enum (PHP Enum)
    $meta->enum('comments_type', CommentType::class)->default(CommentType::HYVOR);
        
}
```

### Default value

You should always set a default value for each metadata.

Use the `default` method to set the default value. The value should be of the same type as the type you defined.

```php
$meta->string('seo_robots')->default('index, follow');
```

Or, you can use `nullable` to set the default value to `null`.

```php
$meta->string('seo_robots')->nullable();
```

In the above example, the type of `seo_robots` is now `string|null`.

### Methods

The `HasMeta` trait adds the following methods to the model.

**metaGet(string $name) : mixed**

To get a meta value by name. The `$name` should be one of the names you defined in the `metaDefinition` function. If the value is not set, the default value will be returned.

```php
$seoIndexingOn = $blog->metaGet('seo_indexing');
```

**metaGetAll() : array**

To get all meta values. All keys you define in `metaDefinition` function will be set in the returned array, filled with default values if missing in the `meta` field.

```php
$meta = $blog->metaGetAll();

if ($meta['seo_indexing']) {
    echo "Hey Google, Please index me!";
}
```

**metaSet(string|array $name, mixed $value) : void**

Set meta `$name` to `$value`. In addition to static type checking via PHPStan, runtime type checking is done here to prevent invalid values from being saved.

```php
$blog->metaSet('seo_indexing', false);
```

```php
// throws an error (wrong type)
$blog->metaSet('seo_indexing', 'no');
```

You may also send an array as the first param to update multiple values.

```php
$blog->metaSet([
    'seo_indexing' => false,
    'comments_type' => CommentType::OTHER 
]);
```

## Types

Include the PHPStan extension to get better type checking.

```neon
includes:
    - vendor/hyvor/laravel-json-meta/extension.neon
```

This registers the generic utility type `meta-of<Model>`. You can use this type to easily get the type of the meta fields as a constant array.

```php
/**
*  @param key-of<meta-of<Blog>> $key
*/
function handleMeta($key) {
    // $key is a key of the meta definition of Blog
}
```

## Why?

Why save metadata in a JSON column?

Let's see other options:

**CASE 1**: You could save metadata in separate columns.

* Maintaining a lot of columns is hard. You would need migrations to add new columns.
* Most of the time, users do not change the default values. So, most of the columns will be empty. It's much better to save them only when they are changed (which is what this library does). See "How data is saved" section below.
* Databases have max row size. In MYSQL it is around 65KB. At [Hyvor Talk](https://talk.hyvor.com), we are closer to this limit, and it is one of the main reasons we developed this library. Note that in this library, we save metadata in TEXT, BLOB, and JSON columns, which are not counted in the row size limit.

**CASE 2**: You could save metadata in a separate table.

This is actually a good option. Check the [laravel-meta](https://github.com/kodeine/laravel-meta) library which has a similar concept like this library but saves data in a separate table.

## How data is saved

When a new model is created, `meta` is `NULL`. Meta column only contains fields that were changed. For example, if you set `seo_indexing` to `false`, you will have the following JSON in the `meta` column:

```json
{
  "seo_indexing": false
}
```

This way, you will save a lot of space in the database.

### Adding new metadata

Let's say you want to add a new metadata called `seo_follow_external_links`. This task is pretty easy. All you have to do is adding this to the definition.

```php
use Hyvor\JsonMeta\MetaDefinition;

public function defineMeta(MetaDefinition $meta) 
{
    // old definitions
    
    // the new one
    $meta->boolean('seo_follow_external_links')->default(false);
   
}
```

### Updating metadata

Updating is a bit tricky. Let's say you want to rename `seo_indexing` to `seo_indexing_on`. You could update the definition, but the problem is with the values that are already saved in the database. 

How do you update this:

```json
{
  "seo_indexing": false
}
```

to this:

```json
{
  "seo_indexing_on": false
}
```

One option is to use JSON operations in the database. Or, use a custom script. Currently, this library does not provide this feature out of the box. But, if required in the future, we will add a command to do this. (Contributions/ideas are welcome)

### Removing metadata

If you need to remove `seo_indexing` option from your application, you should first remove it in the meta definition.

You will still have old data saved in the database meta fields, but it should not be a problem as that data will be not be used even its there.

However, if you are required to delete those data (ex: for legal reasons), you will need an option similar to the "Updating metadata" section. Again, contributions are welcome.