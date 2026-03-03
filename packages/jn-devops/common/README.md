# Homeful Common Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jn-devops/common.svg?style=flat-square)](https://packagist.org/packages/jn-devops/common)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jn-devops/common/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jn-devops/common/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jn-devops/common/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jn-devops/common/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jn-devops/common.svg?style=flat-square)](https://packagist.org/packages/jn-devops/common)

Common tools to Homeful packages.

## Installation

You can install the package via composer:

```bash
composer require jn-devops/common
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="common-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="common-views"
```

## Usage

```php
use Homeful\Common\Traits\HasPackageFactory;

class HomefulModel extends Model
{
    use HasPackageFactory;
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

- [Lester B. Hurtado](https://github.com/jn-devops)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
