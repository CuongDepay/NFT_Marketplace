<?php

namespace App\Http\Services\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getAllUser()
    {
        return User::paginate(12);
    }

    public function getDetailUserById($id, $pageSize)
    {
        $user = User::findOrFail($id);

        $nfts = $user->nfts()->paginate($pageSize);

        return ["user" => $user, "nfts" => $nfts];
    }

    public function getTopSeller()
    {
        return DB::table('order_details')->join('nfts', 'order_details.nft_id', '=', 'nfts.id')
            ->join('collections', 'nfts.collection_id', '=', 'collections.id')
            ->join('users', 'collections.user_id', '=', 'users.id')
            ->select('users.name', 'users.avatar', DB::raw('SUM(nfts.price * order_details.quantity) as total_sales'))
            ->groupBy('users.name', 'users.avatar')
            ->orderByDesc('total_sales')
            ->take(8)
            ->get();
    }

    public function countALlUser()
    {
        return User::count();
    }
}
