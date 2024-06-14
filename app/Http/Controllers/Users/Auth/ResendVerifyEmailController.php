<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Auth\ForgotPasswordRequest;
use App\Http\Services\Users\AuthService;
use Illuminate\Http\Request;

class ResendVerifyEmailController extends Controller
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
     *      path="/api/auth/resend-verify-email",
     *      summary="Send email verification for a user",
     *      tags={"Auth"},
     *      description="Description : Send email verification for a registered user.<br> Author:Tan",
     *      operationId="sendVerifyEmailUser",
     *      security={{"user-session":{}}},
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="Email of the user to resend verification email",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Email verification sent successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Email verification sent successfully"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Email not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User not found"),
     *          ),
     *      ),
     * )
     */
    public function __invoke(ForgotPasswordRequest $request)
    {
        $email = $request->input("email");
        $user = $this->authService->userFindByEmail($email);
        if ($user) {
            $this->authService->sendEmailVerify($user);
            return $this->success("Email verification sent successfully");
        }
        return $this->failure("Email not found", 404);
    }
}
