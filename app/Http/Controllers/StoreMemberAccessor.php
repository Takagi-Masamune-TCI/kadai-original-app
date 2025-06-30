<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;

trait StoreMemberAccessor
{
    protected function canAccessToStore(User|null $user, Store $store)
    {
        return StoreController::canAccess($user, $store);
    }
}
