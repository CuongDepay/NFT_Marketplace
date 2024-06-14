<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Auth\LoginRequest;
use App\Http\Resources\Users\BasicUserInfoResource;
use App\Http\Services\Users\AuthService;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    private AuthService $authService;

    /**
     * Class constructor.
     */

    /**
     * @OA\Info(
     *      title="Tên của API",
     *      version="3.0.0",
     *      description="Mô tả API của bạn"
     * )
     */

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     * path="/api/auth/login",
     * summary="Login a user",
     * tags={"Auth"},
     * description="Description : Logs in a user based on their email and password. Returns the authenticated user information on success.<br>Author: Tan",
     * operationId="loginUser",
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     @OA\Property(property="email", type="string",example="example@gmail.com" ,description="The user's email address."),
     *     @OA\Property(property="password", type="string",example="123456", description="The user's password."),
     *     )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Login successful",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Success message", description="Success message"),
     *         @OA\Property(property="data", type="object", example="{}", description="User information"),
     *     ),
     *     @OA\Header(
     *         header="Set-Cookie",
     *         description="Cookie containing session information",
     *         @OA\Schema(
     *             type="string",
     *             example="user-session=k5nWUxXZnJcL0l1Zz09IiwibWFjIjoiYjcwNTFhYTY3YzRhYjYzZTYyNjZjNzYwNzg0NjQzMWViMDI4MDMwZmY5ZGU4M2EwZDY1ZWM0NjViMmE4YThmZCJ9; expires=Thu, 26-Apr-2024 14:30:00 GMT; Max-Age=7200; path=/; httponly; secure; SameSite=None"
     *         )
     *     )
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthorized", description="Error message"),
     *     )
     * ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Please verify your email before using account.", description="Error message")
     *         )
     *     ),
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

    public function __invoke(LoginRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input("password");

        $user = $this->authService->userFindByEmail($email);

        if ($user && !$user->email_verified_at) {
            return $this->failure("Please verify your email before using account.", 403);
        }

        if ($this->authService->checkAccountLogged($email)) {
            return $this->failure('Account is logged in.Please log out first!', 409);
        }

        $user = $this->authService->login($email, $password);

        if ($user) {
            $cookie = $this->createSessionBaseCookie();

            $userResponse = new BasicUserInfoResource($user);
            return $this->success("login successfully", $userResponse, 200)->withCookie($cookie);
        }
        if (!$user) {
            return $this->failure("Please check your email and password again", 401);
        }
    }

    protected function createSessionBaseCookie()
    {
        $nameCookie = "user-session";
        $sessionId = session()->getId();
        $minutes = config('session.lifetime');
        return Cookie::make($nameCookie, $sessionId, $minutes);
    }
}
