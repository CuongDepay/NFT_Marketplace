<?php

namespace App\Http\Services\NFTs;

use App\Helpers\MessageConstant;
use App\Helpers\MinioHelper;
use App\Models\NFT;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NFTService
{
    public function createNFT($data, $collectionId)
    {
        $nft = new NFT();

        $nft->title = $data["title"];
        $nft->collection_id = $collectionId;
        $nft->image_url = MinioHelper::uploadFile($data["image_url"]);
        $nft->description = $data["description"];
        $nft->starting_date = date('Y-m-d H:i:s', strtotime($data["starting_date"]));
        $nft->expiration_date = date('Y-m-d H:i:s', strtotime($data["expiration_date"]));
        $nft->quantity = $data["quantity"];
        $nft->view = 0;
        $nft->price = $data["price"];

        $nft->save();
        return $nft;
    }

    public function findById($id)
    {
        $nft = NFT::find($id);
        if (!$nft) {
            throw new NotFoundHttpException(MessageConstant::NFT_NOT_FOUND);
        }

        return $nft;
    }
    public function getListNFTByCollectionIdAndFilterWithPaginate($collectionId, $minPrice, $maxPrice, $currentDateTime, $content, $pageSize)
    {
        return NFT::where('collection_id', $collectionId)
            ->when($minPrice, fn ($q) => $q->whereBetween('price', [$minPrice, $maxPrice]))
            ->when($currentDateTime, fn ($q) => $q->where('starting_date', '<=', $currentDateTime)
                ->where('expiration_date', '>=', $currentDateTime))
            ->when($content, fn ($q) => $q->where('title', 'ILIKE', '%' . $content . '%')->orWhere('description', 'ILIKE', '%' . $content . '%'))
            ->paginate($pageSize);
    }

    public function getListNFTAndFilterWithPaginate($minPrice, $maxPrice, $currentDateTime, $content, $pageSize)
    {
        return NFT::query()
            ->when($minPrice, fn($q) => $q->whereBetween('price', [$minPrice, $maxPrice]))
            ->when(
                $currentDateTime,
                fn($q) => $q->where('starting_date', '<=', $currentDateTime)
                    ->where('expiration_date', '>=', $currentDateTime)
            )
            ->when($content, fn($q) => $q->where('title', 'ILIKE', '%' . $content . '%')->orWhere('description', 'ILIKE', '%' . $content . '%'))
            ->paginate($pageSize);
    }

    public function addViewNFT($id)
    {
        $nft = $this->findById($id);

        $nft->view += 1;
        $nft->save();
    }

    public function getTopNFT($categoryId)
    {
        $topNFTs = OrderDetail::select('nft_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('nft_id')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->with(['nft.collection' => function ($query) use ($categoryId) {
                if ($categoryId) {
                    $query->where('category_id', $categoryId);
                }
            }])
            ->get();
        if ($categoryId) {
            $topNFTs = $topNFTs->filter(function ($item) {
                return $item->nft->collection !== null;
            })->values();
        }
        return $topNFTs;
    }

    public function countAllNFT() {
        return NFT::count();
    }
}
