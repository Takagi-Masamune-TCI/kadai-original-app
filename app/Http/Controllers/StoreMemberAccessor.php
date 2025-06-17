<?php

namespace App\Http\Controllers;

use App\Models\Store;

trait StoreMemberAccessor
{
    protected function canAccessToStore(int $userId, Store $store)
    {
        return $store->is_public || $store->created_by == $userId;
    }
}
