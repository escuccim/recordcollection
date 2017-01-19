# RecordCollection

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

A Laravel package to maintain my database of vinyl records in my collection, with a searchable HTML interface and an API interface.

## Install

Via Composer

``` bash
$ composer require escuccim/recordcollection
```

- Register service provider
- Run migrations
- Register middleware

## Usage
This package contains its own routes, models, controllers and views so should run out of the box. I don't really expect anyone else to use it 
other than me so am not going to make this too detailed. 

Once you have everything installed the route /records should take you to the list of records, where you can add or edit records. I do not have
a delete function as I would never get rid of any of my records.

The HTML interface will display a link to discogs and a thumbnail from discogs if you have that info in the database table. If not I wrote scripts I
used to pull the info from Discogs, but due to the large numbers of variations of many vinyl releases it usually needs a bit
of hand-holding to populate usable data.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email skooch@gmail.com instead of using the issue tracker.

## Credits

- [Eric Scuccimarra][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/escuccim/RecordCollection.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/escuccim/RecordCollection/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/escuccim/RecordCollection.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/escuccim/RecordCollection.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/escuccim/RecordCollection.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/escuccim/RecordCollection
[link-travis]: https://travis-ci.org/escuccim/RecordCollection
[link-scrutinizer]: https://scrutinizer-ci.com/g/escuccim/RecordCollection/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/escuccim/RecordCollection
[link-downloads]: https://packagist.org/packages/escuccim/RecordCollection
[link-author]: https://github.com/escuccim
[link-contributors]: ../../contributors
