<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\BasicUserInfoResource;
use App\Http\Services\Users\AuthService;

class VerifyEmailController extends Controller
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
     *      path="/api/auth/verify-email/{token}",
     *      summary="Verify email for a user",
     *      tags={"Auth"},
     *      description="Description : Email verification for a registered user.<br> Author:Tan",
     *      operationId="verifyEmailUser",
     *      @OA\Parameter(
     *          name="token",
     *          in="path",
     *          description="Verification token of the user",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Email verification successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Email verification sent successfully"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid or expired token",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Invalid or expired token"),
     *          ),
     *      ),
     * )
     */

    public function __invoke($token)
    {
        $redirectUrl = "https://web1-nft.d-soft.tech/login";
        if ($user =  $this->authService->verityEmail($token)) {
            return $this->success("Email verification successful", new BasicUserInfoResource($user), 200)->header("Location", $redirectUrl)->setStatusCode(200);
        };

        return $this->failure("Invalid or expired token.", 400);
    }
}
