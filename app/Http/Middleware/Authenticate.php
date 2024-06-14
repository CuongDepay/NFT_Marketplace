<?php

namespace App\Http\Middleware;

use App\Helpers\MessageConstant;
use App\Http\Traits\RespondsWithHttpStatus;
use App\Models\Session;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Authenticate extends Middleware
{
    use RespondsWithHttpStatus;

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $cookie = $request->cookie("user-session");
        if (!$cookie) {
            throw new AuthenticationException(MessageConstant::UNAUTHORIZED);
        }

        $session = Session::all()->find($cookie);

        if (!$session) {
            throw new NotFoundHttpException(MessageConstant::SESSION_NOT_FOUND_OR_NOT_EXISTING);
        }

        $request->merge(['user' => $session->user]);
        return $next($request);
    }
}
