<?php

namespace App\Http\Controllers\UserRelationship;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserRelationship\UserFollowerResource;
use App\Http\Services\Users\UserService;

class GetUserAndFollowersListController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * @OA\Get(
     *     path="/api/user-relationship/users",
     *     summary="Get list user and followers",
     *     tags={"User Relationships"},
     *     description="Get list user and followers.<br>Author: Tan",
     *     operationId="getUserAndFollowerList",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get list user followers successfully."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error occurred."),
     *         ),
     *     ),
     * )
     */


    public function __invoke()
    {
        $users =  $this->userService->getAllUser();
        return $this->success("Get list user followers successfully.", [
            'data' => UserFollowerResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'from' => $users->firstItem(),
                'last_page' => $users->lastPage(),
                'path' => $users->path(),
                "next_page_url" => $users->nextPageUrl(),
                "last_page_url" => $users->previousPageUrl(),
                'per_page' => $users->perPage(),
                'to' => $users->lastItem(),
                'total' => $users->total(),
            ]
        ]);
    }
}
