<?php

namespace App\Http\Services\UserProfile;

use App\Helpers\MessageConstant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserProfileService
{
    public function findById($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            throw new NotFoundHttpException(MessageConstant::USER_NOT_FOUND);
        }

        return $user;
    }


    public function countFollowerByUserId($userId)
    {
        return DB::table('user_relationships')
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->count();
    }

    public function countFollowingByUserId($followerId)
    {
        return DB::table('user_relationships')
            ->where('follower_id', $followerId)
            ->whereNull('deleted_at')
            ->count();
    }
}
