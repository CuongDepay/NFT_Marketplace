<?php

namespace App\Http\Controllers\Users\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\BasicUserInfoResource;
use Illuminate\Http\Request;

class GetProfileController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/users/my-profile",
     * summary="Get info profile of user",
     * tags={"User Profile APIs"},
     * description="Get profile of user.<br>Author: Duc Huy",
     * operationId="getMyProfile",
     * security={{"user-session":{}}},
     * @OA\Response(
     *     response=200,
     *     description="Login successful",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Success message", description="Success message"),
     *         @OA\Property(property="data", type="object", example="{}", description="User information"),
     *     ),
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthorized", description="Error message"),
     *     )
     * ),
     * @OA\Response(
     *     response=409,
     *     description="Account already logged in",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string",example="Account is already logged in. Please log out first!", description="Error message"),
     *     )
     *   ),
     * @OA\Response(
     *      response=422,
     *      description="Error: Unprocessable Content",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Validation error", description="Error message"),
     *          @OA\Property(property="errors", type="object", example="{ 'email': ['The email field is required.'],'password': ['The password field is required.'] }", description="Validation errors"),
     *      )
     *   )
     * ),
     */

    public function __invoke(Request $request)
    {
        $user = new BasicUserInfoResource($request->user());
        return $this->success("Get profile successfully", $user);
    }
}
