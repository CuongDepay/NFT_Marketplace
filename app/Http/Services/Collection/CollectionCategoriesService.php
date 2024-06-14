<?php

namespace App\Http\Services\Collection;

use App\Models\CollectionCategory;

class CollectionCategoriesService
{
    public function getAllCollectionCategories()
    {
        return CollectionCategory::all();
    }
}
