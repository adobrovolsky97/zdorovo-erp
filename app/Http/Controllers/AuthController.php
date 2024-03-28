<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as Status;

/**
 * Class AuthController
 */
class AuthController extends Controller
{
    /**
     * Logout user
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()->token()?->revoke();

        return Response::json(null, Status::HTTP_NO_CONTENT);
    }
}
