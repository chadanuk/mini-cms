# A micro CMS for laravel



[![Latest Version on Packagist](https://img.shields.io/packagist/v/chadanuk/mini-cms.svg?style=flat-square)](https://packagist.org/packages/chadanuk/mini-cms)
[![Build Status](https://img.shields.io/travis/chadanuk/mini-cms/master.svg?style=flat-square)](https://travis-ci.org/chadanuk/mini-cms)
[![Quality Score](https://img.shields.io/scrutinizer/g/chadanuk/mini-cms.svg?style=flat-square)](https://scrutinizer-ci.com/g/chadanuk/mini-cms)
[![Total Downloads](https://img.shields.io/packagist/dt/chadanuk/mini-cms.svg?style=flat-square)](https://packagist.org/packages/chadanuk/mini-cms)

Templates based on page slug name, multiple Markdown and string blocks per page.

Define the content blocks in the template and Mini Cms will pick them up an present them as fields in the admin.


## Installation

You can install the package via composer:

```bash
composer require chadanuk/mini-cms
```

Add the following to your services providers in `config/app.php`

```php
'providers' => [...
    Chadanuk\MiniCms\MiniCmsServiceProvider::class,
    Chadanuk\MiniCms\MiniCmsAdminRouteServiceProvider::class,
    ...
];
```
And at the bottom of all the providers add the catch all cms provider...

```php
'providers' => [...
    Chadanuk\MiniCms\MiniCmsRouteServiceProvider::class,
];
```

Add the following to your aliases in `config/app.php`
```php
'aliases' => [
    'MiniCms' => Chadanuk\MiniCms\MiniCmsFacade::class,
];
```

## Usage

To use the blocks on a template (named after the page slug) in `resources/views/vendor/mini-cms/templates/{page-slug}.blde.php`

```php
<h1>@minicms('string', 'Title')</h1>

<h2>@minicms('string', 'Subtitle')</h2>

@minicms('markdown', 'Content')
```

To embed the minicms admin pages in a custom view you will need to remove the admin route and use the following in your view...

```php
\MiniCms::renderAdmin()
```

You will also need to add a route that catches the minicms paths, so something like...

```php
Route::any('admin/mini-cms/{path?}', '\App\Http\Controllers\Admin\CMSController@show')->name('admin.cms')->where('path', '.*');
```


### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email chadanuk+minicms@gmail.com instead of using the issue tracker.

## Credits

- [Dan Chadwick](https://github.com/chadanuk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
