<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Extends the routing base Controller (rather than only mixing in
 * AuthorizesRequests) so that `$this->middleware()` — used internally by
 * `authorizeResource()` throughout the Admin controllers — is available.
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
