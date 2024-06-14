<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Auth\RegisterRequest;
use App\Http\Services\Users\AuthService;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    private AuthService $authService;

    /**
     * Class constructor.
     */

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user.
     *
     * @OA\Post(
     *      path="/api/auth/register",
     *      summary="Register a user",
     *      tags={"Auth"},
     *      description="Description : Register a new user.<br> Author: Tan",
     *      operationId="registerUser",
     *      @OA\RequestBody(
     *          required=true,
     *          description="User information",
     *          @OA\JsonContent(
     *              required={"name", "email", "password", "password_confirmation"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="password")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful registration",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Registration successful"),
     *              @OA\Property(property="data", type="object", example="{}", description="User information"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="false", description="Indicates if registration failed"),
     *              @OA\Property(property="message", type="string", example="Bad request", description="Error message"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="false", description="Indicates if registration failed"),
     *              @OA\Property(property="message", type="string", example="The given data was invalid.", description="Error message"),
     *              @OA\Property(property="errors", type="object", example="{}", description="Validation errors"),
     *          ),
     *      ),
     * )
     */

    public function __invoke(RegisterRequest $request)
    {
        try {
            $userData = $request->all();
            $user = $this->authService->register($userData);
            event(new Registered($user));
            return $this->success("Registration successful and please verify email", $user, 201);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage(), 500);
        }
    }
}
