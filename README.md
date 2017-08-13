# Laravel ACL

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

Multi Tenant Laravel ACL is a simple Multi Database Multi Tenant - user, company_user, role, permission ACL for SaaS Applications in Laravel Framework.
This package was based on the great package [yajra\laravel-acl](https://github.com/yajra/laravel-acl) but is catered to Multi-Database Multi-Tenant SaaS applications.

<!-- ## Documentations -->
<!-- - [Laravel ACL][link-docs] -->

## 1) Installation

Via Composer

``` bash
$ composer require muhammadsaeedparacha/multi-tenant-laravel-acl
```

## 2) Configuration
#### [i] Register service provider:
``` php
Paracha\Acl\AclServiceProvider::class
```

#### [ii] Register Middlewares: in App\Http\Kernel.php
```php
'canAtLeast' => \Paracha\Acl\Middleware\CanAtLeastMiddleware::class,
'permission' => \Paracha\Acl\Middleware\PermissionMiddleware::class,
'role' => \Paracha\Acl\Middleware\RoleMiddleware::class,
'tenantConnection' => \Paracha\Acl\Middleware\TenantConnectionMiddleware::class,
'checkPermission' => \Paracha\Acl\Middleware\CheckPermissionsMiddleware::class,
```

#### [iii] Set Master & Tenant Connection: in `Config\Database` to be filled on the fly based on tenant:
Note: Currently only Supports MySQL
```php
'tenant' => [
'driver' => 'mysql',
'host' => '',
'port' => env('DB_PORT', '3306'),
'database' => '',
'username' => '',
'password' => '',
'charset' => 'utf8',
'collation' => 'utf8_unicode_ci',
'prefix' => '',
'strict' => true,
'engine' => null,
],
```

#### [iv] Publish assets:
```php
$ php artisan vendor:publish --tag=multi-tenant-laravel-acl
```

#### [v: Optional] Configure your Tenant Migrations Location: in `Config\Acl`. The array defines folders within `Database\Migrations`
``` php
'tenantMigrations' => ['/tenants']
```

#### [vi] Run migrations:
```php
php artisan migrate
```

#### [vii] Put User trait in `App\User` Model:
```php
use Paracha\Acl\Traits\AclUser;
class Company extends Model
{
	use AclUser
}
```

## 3) Documentation
#### Middleware

### Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

### Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

### Security

If you discover any security related issues, please email saeedparacha13@gmail.com instead of using the issue tracker.

### Credits

- [All Contributors][link-contributors]

### License

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