<?php

namespace App\Http\Controllers\UserRelationship;

use App\Http\Controllers\Controller;
use App\Http\Services\UserRelationship\UserRelationshipService;
use Illuminate\Http\Request;

class UnFollowController extends Controller
{
    private UserRelationshipService $userRelationshipService;

    public function __construct(UserRelationshipService $userRelationshipService)
    {
        $this->userRelationshipService = $userRelationshipService;
    }
    /**
     * @OA\Delete(
     *     path="/api/user-relationship/{user_id}",
     *     summary="Unfollow a user",
     *     tags={"User Relationships"},
     *     description="remove follow a user.<br>Author: Tan",
     *     operationId="UnFollow",
     *     security={{"user-session":{}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to follow",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="UnFollowed user successfully."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized", description="Error message"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No follow relationship found to unfollow.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No follow relationship found to unfollow.", description="Error message"),
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


    public function __invoke(Request $request, $userId)
    {
        $followerId = $request->user()->id;
        $dataUserRelationship = [
            "follower_id" => $followerId,
            "user_id" => $userId,
        ];
        if ($this->userRelationshipService->removeFollow($dataUserRelationship)) {
            return $this->success("Unfollowed user successfully.");
        }
        return $this->failure("No follow relationship found to unfollow.", 404);
    }
}
