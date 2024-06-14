<?php

namespace App\Http\Services\Collection;

use App\Helpers\MessageConstant;
use App\Helpers\MinioHelper;
use App\Models\Collection;
use App\Models\CollectionCategory;
use App\Models\Session;
use Carbon\Traits\Timestamp;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;

class CollectionService
{
    public function createCollection($data)
    {
        try {
            $collection = Collection::create([
                "name" => $data["name"],
                "url" => $data["url"],
                "description" => $data["description"],
                "price" => $data["price"],
                "logo_image_url" => MinioHelper::uploadFile($data["logo_image_url"]),
                "featured_image_url" => MinioHelper::uploadFile($data["featured_image_url"]),
                "cover_image_url" => MinioHelper::uploadFile($data["cover_image_url"]),
                "category_id" => $data["category_id"],
                "starting_date" => date('Y-m-d H:i:s', strtotime($data["starting_date"])),
                "expiration_date" => date('Y-m-d H:i:s', strtotime($data["expiration_date"])),
                "user_id" => $data["user_id"],
            ]);

            return $collection;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findByIdAndUserId($id, $userId)
    {
        $collection = Collection::where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$collection) {
            throw new NotFoundHttpException(MessageConstant::COLLECTION_NOT_FOUND);
        }

        return $collection;
    }

    public function findById($id)
    {
        $collection = Collection::with(['nfts', 'user', 'category'])->find($id);

        if (!$collection) {
            throw new NotFoundHttpException(MessageConstant::COLLECTION_NOT_FOUND);
        }
        $collection->volume = $collection->nfts->sum('price');
        $collection->floor_price = $collection->nfts->min('price');
        return $collection;
    }

    public function getListCollection($categoryId = null, $searchText = null)
    {
        $query = Collection::with('nfts');
        if (!is_null($categoryId)) {
            $query->where('collections.category_id', $categoryId);
        }

        if (!is_null($searchText)) {
            $query->where(function ($q) use ($searchText) {
                $q->where('collections.name', 'like', "%{$searchText}%")
                    ->orWhere('collections.description', 'like', "%{$searchText}%");
            });
        }

        $collections = $query->paginate(10);

        $collections->getCollection()->transform(function ($collection) {
            $collection->volume = $collection->nfts->sum('price');
            $collection->floor_price = $collection->nfts->min('price');
            return $collection;
        });
        return $collections;
    }

    public function getMyCollection($userId)
    {
        $collections = Collection::where('user_id', $userId)->get();
        if ($collections->isEmpty()) {
            return [];
        }

        return $collections;
    }

    public function getTopCollections($from, $to)
    {
        return $query = Collection::selectRaw('collections.id, collections.name')
            ->selectRaw('(SELECT SUM(nfts.price) FROM nfts WHERE nfts.collection_id = collections.id) as volume')
            ->selectRaw('MIN(nfts.price) as floor_price')
            ->selectRaw('COALESCE(SUM(CASE WHEN order_details.created_at BETWEEN ? AND ? THEN order_details.quantity ELSE 0 END), 0) as sales', [$from, $to])
            ->selectRaw('COUNT(DISTINCT orders.user_id) as unique_owners')
            ->selectRaw('COUNT(DISTINCT nfts.id) as items_listed')
            ->join('nfts', 'nfts.collection_id', '=', 'collections.id')
            ->leftJoin('order_details', 'order_details.nft_id', '=', 'nfts.id')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->groupBy('collections.id')
            ->orderBy('sales', 'desc')
            ->get();
    }
}
