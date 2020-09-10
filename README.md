# Steam Auth/Login for Laravel 6.0+

[![Packagist](https://img.shields.io/packagist/dt/skyraptor/laravel-steam-login.svg?style=flat-square&maxAge=3600)](https://packagist.org/packages/skyraptor/laravel-steam-login)
[![Packagist version](https://img.shields.io/packagist/v/skyraptor/laravel-steam-login.svg?style=flat-square)](https://packagist.org/packages/skyraptor/laravel-steam-login)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/skyraptor/laravel-steam-login.svg?style=flat-square)](https://packagist.org/packages/skyraptor/laravel-steam-login)
[![GitHub stars](https://img.shields.io/github/stars/skyraptor/laravel-steam-login.svg?style=flat-square)](https://github.com/skyraptor/laravel-steam-login/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/skyraptor/laravel-steam-login.svg?style=flat-square)](https://github.com/skyraptor/laravel-steam-login/network)
[![GitHub issues](https://img.shields.io/github/issues/skyraptor/laravel-steam-login.svg?style=flat-square)](https://github.com/skyraptor/laravel-steam-login/issues)
[![GitHub license](https://img.shields.io/github/license/skyraptor/laravel-steam-login.svg?style=flat-square)](https://github.com/skyraptor/laravel-steam-login/blob/master/LICENSE)

Make sure you have made/performed your migrations along with updating your `User` model if you plan to follow the examples. I suggest doing whatever works best for you, but certain suggestions should be followed.

| Version | Laravel Version | Docs |
| ------- | --------------- | ---- |
| 1.x     | >5.6            | [Docs](https://github.com/Deutsche-Squad-Gemeinschaft/laravel-steam-login/wiki/1.x) |
| 2.x     | 5.6+            | [Docs](https://github.com/Deutsche-Squad-Gemeinschaft/laravel-steam-login/wiki/2.x) |

## Features
  - Redirect users to the page they were on before logging in
  - `SteamUser`class to easily retrieve a player's data
  - Included controller and routes for easy setup

## Quick Setup (2.x, Laravel 5.6+)

1. Install library
```
composer require skyraptor/laravel-steam-login

php artisan vendor:publish --force --provider skyraptor\LaravelSteamLogin\SteamLoginServiceProvider
```

2. Add routes

`routes/web.php`
```php
use App\Http\Controllers\Auth\SteamLoginController;
use skyraptor\LaravelSteamLogin\Facades\SteamLogin;

//...

SteamLogin::routes(['controller' => SteamLoginController::class]);
```
```
php artisan make:controller Auth\SteamLoginController
```
`App\Http\Controllers\Auth\SteamLoginController.php`
```php
<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use skyraptor\LaravelSteamLogin\Http\Controllers\AbstractSteamLoginController;
use skyraptor\LaravelSteamLogin\SteamUser;

class SteamLoginController extends AbstractSteamLoginController
{
    /**
     * {@inheritdoc}
     */
    public function authenticated(Request $request, SteamUser $steamUser)
    {
        // auth logic goes here
        // e.g. $user = User::where('steam_account_id', $steamUser->accountId)->first();
    }
}
```
---

## Credits

Thanks to these libs which led me to make this
- https://github.com/kanalumaddela/laravel-steam-login (original author)
- https://github.com/Ehesp/Steam-Login (Parts of code used and re-purposed for laravel)
- https://github.com/invisnik/laravel-steam-auth
