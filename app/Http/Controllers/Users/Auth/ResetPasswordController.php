<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Auth\ResetPasswordRequest;
use App\Http\Services\Users\AuthService;
use Carbon\Carbon;

class ResetPasswordController extends Controller
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
     * @OA\Post(
     * path="/api/auth/reset-password/{token}",
     * summary="Reset user's password",
     * tags={"Auth"},
     * description="Description : Reset user's password using a valid password reset token.<br>Author: Tan",
     * operationId="resetPasswordUser",
     * @OA\Parameter(
     *     name="token",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string",
     *         example="abcdef123456",
     *     ),
     *     description="The password reset token.",
     * ),
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     @OA\Property(property="password", type="string",example="123456", description="The user's password."),
     *     @OA\Property(property="password_confirmation", type="string",example="123456" ,description="he user's password confirmation."),
     *     )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Reset password successful",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Success message", description="Success message"),
     *     ),
     * ),
     * @OA\Response(
     *      response=400,
     *      description="Error: Bad request",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="This password reset token is invalid.", description="This password reset token is invalid."),
     *      ),
     *   ),
     * @OA\Response(
     *      response=422,
     *      description="Error: Unprocessable Content",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Validation error", description="Error message"),
     *          @OA\Property(property="errors", type="object", example="{ 'email': ['The email field is required.'],'password': ['The password field is required.'] }", description="Validation errors"),
     *      )
     *   ),
     * @OA\Response(
     *      response=500,
     *      description="Error: Failed to reset password",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Failed to reset password", description="Failed to reset password"),
     *      )
     *   ),
     * ),
     */

    public function __invoke(ResetPasswordRequest $request, $token)
    {
        $password = $request->input("password");
        $passwordResetToken = $this->authService->getPasswordResetToken($token);

        if (Carbon::parse($passwordResetToken->created_at)->addMinutes(60)->isPast()) {
            return $this->failure("This password reset token is invalid.", 400);
        }

        if ($this->authService->updatePasswordAndRemoveToken($passwordResetToken, $password)) {
            return $this->success("Reset password successful");
        }

        return $this->failure("Failed to reset password", 500);
    }
}
