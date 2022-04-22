# Laravel JSON Meta

A library for saving metadata in a JSON column, with type checking.

* Only for databases that support JSON columns
* Best for saving configuration options
* Nested objects are not supported

## Installation

```bash
composer install hyvor/laravel-json-meta
```



## Usage

Let's do this as a tutorial. The plan is to save metadata of `blogs` in a JSON column named `meta`.

First, add a JSON `meta` column to the table.

```php
Schema::create('blogs', function (Blueprint $table) {
    // other columns
    
    $table->json('meta')->nullable();
});
```

### Metadata or column?

When saving data, you have to decide between meta vs column.  Our general rule is that if a data is needed for a `WHERE` or `ORDER BY`, save it in a column. If not, you may save it in metadata. In most cases, configuration options are the best things to save in metadata.

(You can still use metadata in `WHERE` queries because we are saving them in a JSON column.)

### Update the Model

* Add `Metable` trait
* Declare the `metaDefinition` method

```php
use Hyvor\JsonMeta\Metable;
use Hyvor\JsonMeta\Definer;

class Blog extends Model
{
    
    use Metable;
    
    protected function metaDefinition(Definer $definer) 
    {
        // definition goes here
    }

}
```

### Definition

"Definition" is which data you allow saving in the `meta` field. By defining them, you can make sure that wrong data is never inserted by wrong user input or typos in your code.

You can also define types and default values in the definition.

```php
protected function metaDefinition(Definer $definer)
{

    $definer->add('seo_indexing')
        ->type('bool')
        ->default(true);
        
    $definer->add('seo_robots')
        ->type('string|null')
        ->default(null);
        
    $definer->add('comments_type')
        ->type('enum:hyvor,other')
        ->default('hyvor');
        
}
```

This example adds 3 keys. So, your meta field can have these 3 columns, with the declared type.

### Types

The following types are supported:

* `string`
* `int`
* `float`
* `bool`
* `null`
* `enum`

You can combine types using `|` (works like `OR`).

```php
->type('string|null')
```

or, you can send use an array

```php
->type(['string', 'null'])
```

enums are defined as follows

```php
->type('enum:value1,value2');
```

### Methods

The `Metable` trait adds the following methods to the model.

**getMeta(string $name) : mixed**

To get a meta value. If the value is not found in meta field, the default value be returned.

```php
$seoIndexingOn = $blog->getMeta('seo_indexing');
```

**getAllMeta() : object**

To get all meta values. All keys you define in `metaDefinition` function will be set in the returned object, filled with default values if missing in the `meta` field.

```php
$meta = $blog->getAllMeta();

if ($meta->seo_indexing) {
    echo "Hey Google, Please index me!";
}
```

**setMeta(string|array $name, mixed $value) : void**

Set meta `$name` to `$value`. This is where **type checking** happens. This function makes sure invalid values are not saved in the meta field.

```php
$blog->setMeta('seo_indexing', false);
```

```php
// throws an error (wrong type)
$blog->setMeta('seo_indexing', 'no');
```

Or, you may send an indexed array as the first param to update multiple values.

```php
$blog->setMeta([
    'seo_indexing' => false,
    'comments_type' => 'hyvor'
]);
```

## Why?

Why save metadata in a JSON column?

**CASE 1**: You could save metadata in separate columns. Here are the downsides.

* Maintain that amount of columns is not an easy task
* Most of those columns are set to the default value. In most cases, the default values are not changed.
* Databases have max row size. In MYSQL it is around 65KB. We are close to this limit at [Hyvor Talk](https://talk.hyvor.com) because of multiple VARCHAR columns, so we created this library to make sure that does not happen at [Hyvor Blogs](https://blogs.hyvor.com). (TEXT, BLOB, and JSON column sizes are not counted for max row size)

**CASE 2**: You could save metadata in a separate table.

This is actually a good option. Check the [laravel-meta](https://github.com/kodeine/laravel-meta) library which has a similar concept like this library but saves data in a separate table.

## Metadata Definition Updates

Before talking about updates, it is important to understand that the `meta` field is `nullable`, which means that the default value is `NULL`. So, when a blog is created (in the earlier example), `meta` field is `NULL`. It does not contain any information. So, no storage is used. This is another benefit of this approach. Metadata is only added when the values are changed from the default value.

In Hyvor Blogs, there are plenty of customization options. But, most users do not mess with them. So, only the updated values are saved in the meta field.

If the user updates the `seo_indexing` value, the meta field look like this.

```json
{
  "seo_indexing": false
}
```

### Adding new metadata

Let's say you want to add a new metadata called `seo_follow_external_links`. This task is pretty easy. All you have to do is adding this to the definition.

```php
protected function metaDefinition(Definer $definer) 
{
    // old definitions
    
    // the new one
    $definer->add('seo_follow_external_links')
        ->type('bool')
        ->default(false);
    
}
```

### Updating metadata

Good news end here. Updating is not easy.

Let's say you want to rename `seo_indexing` to `seo_indexing_on`. You could update the definition, but the problem is with the values that are already saved in the database. 

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

You will probably need a custom script to do that. Currently, this library does not provide any such thing. But, if required in the future, we will add a command to do this. (Contributions are welcome)

### Removing metadata

If you need to remove `seo_indexing` option from your application, you should first remove it in the meta definition.

You will still have old data saved in the database meta fields, but it should not be a problem as that data will be not be used even its there.

However, if you are required to delete those data (maybe for legal reasons), you will again need a custom script to do that. But, this will be easier than updating. 