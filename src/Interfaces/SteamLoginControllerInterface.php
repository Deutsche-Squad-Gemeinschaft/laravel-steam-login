<?php
/**
 * Laravel Steam Login.
 *
 * @link      https://www.maddela.org
 * @link      https://github.com/kanalumaddela/laravel-steam-login
 *
 * @author    kanalumaddela <git@maddela.org>
 * @copyright Copyright (c) 2018-2019 Maddela
 * @license   MIT
 */

namespace skyraptor\LaravelSteamLogin\Interfaces;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use skyraptor\LaravelSteamLogin\SteamUser;

/**
 * Use \skyraptor\LaravelSteamLogin\Contracts\SteamLoginControllerInterface.
 *
 * @deprecated
 */
interface SteamLoginControllerInterface
{
    /**
     * Redirect the user to the Steam login page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToSteam(): RedirectResponse;

    /**
     * Authenticate the incoming request.
     *
     * @return mixed
     */
    public function authenticate();

    /**
     * Called when the request is successfully authenticated.
     *
     * @param \Illuminate\Http\Request               $request
     * @param \skyraptor\LaravelSteamLogin\SteamUser $steamUser
     *
     * @return mixed|void
     */
    public function authenticated(Request $request, SteamUser $steamUser);
}
