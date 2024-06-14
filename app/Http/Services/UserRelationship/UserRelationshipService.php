<?php

namespace App\Http\Services\UserRelationship;

use App\Helpers\NotificationHelper;
use App\Models\UserRelationship;

class UserRelationshipService
{
    public function isExistUserRelationship($dataUserRelationship)
    {
        return UserRelationship::where($dataUserRelationship)->exists();
    }

    public function addFollow($dataUserRelationship)
    {
        UserRelationship::create($dataUserRelationship);
    }

    public function removeFollow($dataUserRelationship)
    {
        return UserRelationship::where($dataUserRelationship)->delete();
    }
}
