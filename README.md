# Laravel Steam Login

[![Packagist](https://img.shields.io/packagist/dt/skyraptor/laravel-steam-login.svg?style=flat-square&maxAge=3600)](https://packagist.org/packages/skyraptor/laravel-steam-login)
[![Packagist version](https://img.shields.io/packagist/v/skyraptor/laravel-steam-login.svg?style=flat-square)](https://packagist.org/packages/skyraptor/laravel-steam-login)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/skyraptor/laravel-steam-login.svg?style=flat-square)](https://packagist.org/packages/skyraptor/laravel-steam-login)
[![GitHub stars](https://img.shields.io/github/stars/Deutsche-Squad-Gemeinschaft/laravel-steam-login.svg?style=flat-square)](https://github.com/Deutsche-Squad-Gemeinschaft/laravel-steam-login/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/Deutsche-Squad-Gemeinschaft/laravel-steam-login.svg?style=flat-square)](https://github.com/Deutsche-Squad-Gemeinschaft/laravel-steam-login/network)
[![GitHub issues](https://img.shields.io/github/issues/Deutsche-Squad-Gemeinschaft/laravel-steam-login.svg?style=flat-square)](https://github.com/Deutsche-Squad-Gemeinschaft/laravel-steam-login/issues)
[![GitHub license](https://img.shields.io/github/license/Deutsche-Squad-Gemeinschaft/laravel-steam-login.svg?style=flat-square)](https://github.com/Deutsche-Squad-Gemeinschaft/laravel-steam-login/blob/master/LICENSE)

**A light package to provide easy authentication with the Steam API to your Laravel project.**

## Features
  - Redirect users to the page they were on before logging in
  - `SteamUser`class to easily retrieve a player's data
  - Included controller and routes for easy setup

## Installation

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
