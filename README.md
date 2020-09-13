# Laravel Steam Login

[![StyleCI](https://styleci.io/repos/240756053/shield?branch=master)](https://styleci.io/repos/240756053)
[![Total Downloads](https://poser.pugx.org/skyraptor/laravel-steam-login/downloads.png)](https://packagist.org/packages/skyraptor/laravel-steam-login)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/laravel-steam-login/v/stable)](https://packagist.org/packages/skyraptor/laravel-steam-login)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/laravel-steam-login/v/unstable)](https://packagist.org/packages/skyraptor/laravel-steam-login)
[![License](https://poser.pugx.org/skyraptor/laravel-steam-login/license)](https://packagist.org/packages/skyraptor/laravel-steam-login)

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
