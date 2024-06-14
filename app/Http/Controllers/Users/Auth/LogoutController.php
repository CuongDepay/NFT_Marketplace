<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\Users\AuthService;
use Illuminate\Http\Request;

class LogoutController extends Controller
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
    * path="/api/auth/logout",
    * summary="Logout a user",
    * tags={"Auth"},
    * description="Description : Logout a user by.<br>Author: Tan",
    * operationId="logoutUser",
    * security={{"user-session":{}}},
    *     @OA\Parameter(
    *         name="user-session",
    *         in="header",
    *         required=true,
    *         description="User session token",
    *         @OA\Schema(
    *             type="string",
    *         )
    *     ),
    * @OA\Response(
    *     response=200,
    *     description="Logout successful",
    *     @OA\JsonContent(
    *         @OA\Property(property="message", type="string", example="Logout successful", description="Success message"),
    *         @OA\Property(property="data", type="object", example="{}", description="User information"),
    *     ),
    * ),
    *     @OA\Response(
    *         response=400,
    *         description="Logout failed",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example="false", description="Indicates if logout failed"),
    *             @OA\Property(property="message", type="string", example="Logout failed", description="Error message"),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized",
    *     ),
    * ),
    */

    public function __invoke(Request $request)
    {
        $userId =  $request->user()->id;
        if ($this->authService->logout($userId)) {
            return response()->json([
                "success" => true,
                'message' => "Logout Successfully"
            ], 200);
        }
        return response()->json([
            "success" => false,
            'message' => "Logout Failed"
        ], 400);
    }
}
