# RecordCollection

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

A Laravel package to maintain my database of vinyl records in my collection, with a searchable HTML interface and an API interface. Currently has English and French language, easily expandable with additional languages.

## Install

Via Composer

``` bash
$ composer require escuccim/recordcollection
```

Register service provider in config/app.php:
```php
Escuccim\RecordCollection\RecordCollectionServiceProvider::class
Laracasts\Flash\FlashServiceProvider::class,
```
Run migrations to create the database table:
```bash
php artisan migrate
```

This uses a middleware to determine if the user is authorized to perform administrative tasks. You can use mine or create your own, the code references middleware 'admin', so unless there is a middleware with this name registered will throw errors. To use mine register it in app/Http/Kernel.php:
```php
'admin' => \Escuccim\RecordCollection\Http\Middleware\AdminMiddleware::class,
```
To enable some Javascript features you need to add the following to the header of your layouts/app.blade.php file:
```php
<script src="/js/app.js"></script>
@stack('scripts')
```
And you need to remove the script tag referencing app.js from the bottom of the layout file.

You must publish the config and pagination files with:
```bash
php artisan vendor:publish --tag=config
```
If you wish to edit the language or view files you can also publish those:
```bash
php artisan vendor:publish
```
**Note** - You must publish the config files for the search interface to work properly. There are some Javascript assets which need to be put into the public directory for the search interface to work, and it will also publish the Laravel pagination files have some slight modifications which are necessary for proper functioning of the page.

There are multiple groups of files to be published, which you can choose by adding --tags=[group] to the command:
- config - publishes the config file to /config/records.php
- migrations - publishes the database migrations to /database/migrations
- lang - publishes the translation files to /resources/lang/vendor - currently I have files for English and French
- views - publishes the views /resoures/vendor/views

## Usage
This package contains its own routes, models, controllers and views so should run out of the box. To enable the Javascript functions in the admin pages you need to add the following to your layouts/app.blade.php in the header:
```
@yield('header')
```
Once you have everything installed the route /records should take you to the list of records, where you can add or edit records. I do not have a delete function as I would never get rid of any of my records.

The HTML interface will display a link to discogs and a thumbnail from discogs if you have that info in the database table. If not I wrote scripts I used to pull the info from Discogs, but due to the large numbers of variations of many vinyl releases it usually needs a bit of hand-holding to populate usable data.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email skooch@gmail.com instead of using the issue tracker.

## Credits

- [Eric Scuccimarra][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/escuccim/recordcollection.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/escuccim/RecordCollection/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/escuccim/RecordCollection.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/escuccim/RecordCollection.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/escuccim/recordcollection.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/escuccim/recordcollection
[link-travis]: https://travis-ci.org/escuccim/recordcollection
[link-scrutinizer]: https://scrutinizer-ci.com/g/escuccim/recordcollection/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/escuccim/recordcollection
[link-downloads]: https://packagist.org/packages/escuccim/recordcollection
[link-author]: https://github.com/escuccim
[link-contributors]: ../../contributors
