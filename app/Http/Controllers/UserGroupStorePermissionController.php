<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserGroupStorePermissionController extends Controller
{
    // POST user_group/{id}/store_permissions
    public function store(Request $request, string $id)
    {
        $request->validate([
            "storeId" => "required"
        ]);

        $userGroupId = intval($id);
        $storeId = intval($request->input("storeId"));
        
        $store = $request->user()->accessibleStores()->findOrFail($storeId);

        // # 自身がオーナーのグループに追加する
        $userGroup = $request->user()->ownedGroups()->find($userGroupId);
        $userGroup->accessPermittedStores()->attach($store->id);

        return back();
    }

    // DELETE user_group/{id}/store_permissions
    public function delete(Request $request, string $id)
    {
        $request->validate([
            "storeId" => "required"
        ]);

        $userGroupId = intval($id);
        $storeId = intval($request->input("storeId"));
        
        $store = $request->user()->accessibleStores()->findOrFail($storeId);

        // # 自身がオーナーのグループを更新する
        $userGroup = $request->user()->ownedGroups()->find($userGroupId);
        $userGroup->accessPermittedStores()->detach($id);
    }
}
