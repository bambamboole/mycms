# This is my package mycms

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bambamboole/mycms.svg?style=flat-square)](https://packagist.org/packages/bambamboole/mycms)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/bambamboole/mycms/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bambamboole/mycms/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/bambamboole/mycms/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/bambamboole/mycms/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bambamboole/mycms.svg?style=flat-square)](https://packagist.org/packages/bambamboole/mycms)

MyCms is my package for a simple CMS. It is based on Laravel and Filament.  
Additionally it is a bit of a patch work of different packages.
My goal for MyCms is to be a simple CMS which can be used as a starting point for a new project.
MyCms is currently under heavy development and can change quickly.  

## Installation

You can install the package via composer:

```bash
composer require bambamboole/mycms
```


You can install mycms via its own artisan command. Afterwards you may run the migrations

```bash
php artisan mycms:install
php artisan migrate
```

To enable the Plugin in Filament you have to add the plugin to your ServiceProvider

```php

## Usage

```php
//...
$panel->plugin(MyCmsPlugin::make());
//...
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [bambamboole](https://github.com/bambamboole)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
