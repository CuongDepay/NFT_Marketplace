<?php

namespace App\Http\Middleware;

use App\Http\Traits\RespondsWithHttpStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmail
{
    use RespondsWithHttpStatus;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem người dùng đã xác minh email chưa
        if ($request->user() && !$request->user()->hasVerifiedEmail()) {
            return $this->failure("Please verify your email before using account.", 403);
        }

        return $next($request);
    }
}
