<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserResource;
use Auth;

/**
 * Class UserController
 */
class UserController extends Controller
{
    /**
     * Get user data
     *
     * @return UserResource
     */
    public function me(): UserResource
    {
        return UserResource::make(Auth::user());
    }
}
