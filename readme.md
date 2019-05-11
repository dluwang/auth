# Auth
[![Build Status](https://travis-ci.org/dluwang/auth.svg?branch=master)](https://travis-ci.org/dluwang/auth)
[![Latest Stable Version](https://poser.pugx.org/dluwang/auth/v/stable)](https://packagist.org/packages/dluwang/auth)
[![License](https://poser.pugx.org/dluwang/auth/license)](https://packagist.org/packages/dluwang/auth)

Laravel extended auth mechanism. This package is intended to provide dynamic, database-driven authorization mechanism. Gates and Policies will be registered to database, mapped with respective roles and users.

### Installation
```
composer require dluwang/auth
```

Run `php artisan dluwang-auth:install` to generate all necessary files. This command will generate migration files, Role, and Permission class. You can edit the files before running migrate command to match your requirement. Add provided `Dluwang\Auth\Concerns\Assignable` trait to your assignable class (it's your user class for most cases).

```php
use Dluwang\Auth\Concerns\Assignable;

class User extends Authenticatable
{
    use Assignable;
}
```

### Usage

To check if user has permissions you can use `authorized` method. This method can take string or array argument.

```php
$user->authorized('permissions');
$user->authorized(['permission-one', 'permission-two']);
```

To check if user has at least one given permissions, you can use `authorizedOneOf` method.

```php
$user->authorizedOneOf(['permission-one', 'permission-two']);
```

You can also seamlessly integrate this package with Laravel's built in authorization feature. This package can collect registered gates and policies by running

```
php artisan dluwang-auth:collect-permissions
```

### Configuration

This package provide configuration to customize your needs. You can publish the configuration by running command below.
```
php artisan vendor:publish
```

### Test
This repository provide docker-compose file to create isolated environment. To perform test you can use run

```
vendor/bin/phpunit
```