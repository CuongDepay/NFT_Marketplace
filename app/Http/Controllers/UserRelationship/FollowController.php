<?php

namespace App\Http\Controllers\UserRelationship;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\UserRelationship\UserRelationshipService;
use App\Models\NotificationCategory;
use App\Models\UserRelationship;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    private UserRelationshipService $userRelationshipService;

    public function __construct(UserRelationshipService $userRelationshipService)
    {
        $this->userRelationshipService = $userRelationshipService;
    }
    /**
     * @OA\Post(
     *     path="/api/user-relationship/{user_id}",
     *     summary="Follow a user",
     *     tags={"User Relationships"},
     *     description="Add follow a user.<br>Author: Tan",
     *     operationId="addFollow",
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
     *             @OA\Property(property="message", type="string", example="Followed user successfully."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Already following this user",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Already following this user", description="Error message"),
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
        $follower = $request->user();
        $dataUserRelationship = [
            "follower_id" => $follower->id,
            "user_id" => $userId,
        ];



        if ($this->userRelationshipService->isExistUserRelationship($dataUserRelationship)) {
            return $this->failure("Already following this user.", 400);
        }
        $this->userRelationshipService->addFollow($dataUserRelationship);

        $data = NotificationHelper::createNotificationData(
            "New follower By ".$follower->name,
            "Hello",
            NotificationCategory::all()[2]->id,
            $follower,
            $userId,
        );
        NotificationHelper::SendNotification($data);
        return $this->success("Followed user successfully.", [], 201);
    }
}
