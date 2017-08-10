# Laravel ACL

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

Multi Tenant Laravel ACL is a simple Multi Database Multi Tenant - user, company_user, role, permission ACL for SaaS Applications in Laravel Framework.
This package was based on the great package [yajra\laravel-acl](https://github.com/yajra/laravel-acl) but is catered to Multi-Database Multi-Tenant SaaS applications.

## Documentations
- [Laravel ACL][link-docs]

## Installation

Via Composer

``` bash
$ composer require muhammadsaeedparacha/multi-tenant-laravel-acl
```

## Configuration
Register service provider:
``` php
Paracha\Acl\AclServiceProvider::class
```

Register Middlewares: in App\Http\Kernel.php
```php
'canAtLeast' => \Paracha\Acl\Middleware\CanAtLeastMiddleware::class,
'permission' => \Paracha\Acl\Middleware\PermissionMiddleware::class,
'role' => \Paracha\Acl\Middleware\RoleMiddleware::class,
'checkPermission' => \Paracha\Acl\Middleware\CheckPermissionsMiddleware::class,
```

Publish assets:
```php
$ php artisan vendor:publish --tag=multi-tenant-laravel-acl
```

Run migrations:
```php
php artisan migrate
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email saeedparacha13@gmail.com instead of using the issue tracker.

## Credits

- [All Contributors][link-contributors]

## License

The Apache-2.0 License. Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/muhammadsaeedparacha/multi-tenant-laravel-acl.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/Liscence-Apache--2.0-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/muhammadsaeedparacha/multi-tenant-laravel-acl/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/muhammadsaeedparacha/multi-tenant-laravel-acl.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/muhammadsaeedparacha/multi-tenant-laravel-acl
[link-travis]: https://travis-ci.org/muhammadsaeedparacha/multi-tenant-laravel-acl
[link-downloads]: https://packagist.org/packages/muhammadsaeedparacha/multi-tenant-laravel-acl
[link-author]: https://github.com/muhammadsaeedparacha
[link-contributors]: ../../contributors
[link-docs]: https://yajrabox.com/docs/laravel-acl/3.0