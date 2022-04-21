# Laravel JSON Meta

A library for saving metadata in a JSON column, with type checking.

* Only for databases that support JSON columns
* Nested objects are not supported

## Installation

```
composer install hyvor/laravel-json-meta
```

Add a `JSON` `"meta"` column to the table.

```
Schema::create('blogs', function (Blueprint $table) {
    // other columns
    
    $table->json('meta')->nullable();
});
```

## Usage

So, our plan is to save metadata of the `blogs` in a JSON column named `meta`.

### Meta or column?

The first decision you have to make is where to save a piece of data: in a column or as metadata?

Our general rule is that if a data is needed for a `WHERE` or `ORDER BY`, save it in a column. If not, you may save it in metadata. In most cases, configuration options are the best things to save in metadata.

Also, note that this library is designed to not have nested JSON objects. So, there's only one level. Best for configuration options.

### Update Model

* Add `Metable` trait
* Declare `metableDefinition` function

```php
use Hyvor\JsonMeta\Metable;

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

"Definition" is what data you allow to save in the `meta` field. By defining them, you can make sure that wrong data is not inserted by wrong user input or typos in your code.

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
* `enum:val1,val2,val3`

You can combine types using `|` (works like `OR`).

```php
->type('string|null')
```

or, you can send use an array

```php
->type(['string', 'null'])
```

### Methods

The `Metable` trait adds the following methods to the model.

**getMeta(string $name) : mixed**

To get a meta value. If the value is not found in meta field, the default value be returned.

```php
$seoIndexingOn = $blog->getMeta('seo_indexing');
```

**getMetas() : object**

To get all meta values. All keys you define in `metaDefinition` function will be set in the returned object, filled with default values if missing in the `meta` field.

```php
$meta = $blog->getMetas();

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