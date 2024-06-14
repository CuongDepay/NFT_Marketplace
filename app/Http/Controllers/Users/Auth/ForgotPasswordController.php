<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Auth\ForgotPasswordRequest;
use App\Http\Services\Users\AuthService;
use App\Jobs\SendResetPasswordMail;

class ForgotPasswordController extends Controller
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
    * @OA\Get(
    * path="/api/auth/forgot-password",
    * summary="Forgot password a user",
    * tags={"Auth"},
    * description="Description : Initiates the process of resetting a user's password by sending a password reset email.<br>Author: Tan",
    * operationId="forgotPasswordUser",
    * @OA\Parameter(
    *     name="email",
    *     in="query",
    *     required=true,
    *     @OA\Schema(
    *         type="string",
    *         example="example@gmail.com",
    *     ),
    *     description="The user's email address."
    * ),
    * @OA\Response(
    *     response=200,
    *     description="Send email reset password successful",
    *     @OA\JsonContent(
    *         @OA\Property(property="message", type="string", example="Success message", description="Success message"),
    *     ),
    * ),
    * @OA\Response(
    *     response=404,
    *     description="Not found",
    *     @OA\JsonContent(
    *         @OA\Property(property="message", type="string", example="Not found", description="Email Not found"),
    *     ),
    * ),
    * @OA\Response(
    *      response=422,
    *      description="Error: Unprocessable Content",
    *      @OA\JsonContent(
    *          @OA\Property(property="message", type="string", example="Validation error", description="Error message"),
    *          @OA\Property(property="errors", type="object", example="{ 'email': ['The email field is required.'],'password': ['The password field is required.'] }", description="Validation errors"),
    *      )
    *   ),
    * ),
    */

    public function __invoke(ForgotPasswordRequest $request)
    {
        $response = $this->authService->createTokenPasswordReset($request->input("email"));
        $user = $response["user"];
        $token = $response["token"];

        if ($token) {
            dispatch(new SendResetPasswordMail($user, $token));
        }

        return $this->success("We have e-mailed your password reset link!");
    }
}
