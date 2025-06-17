<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * [GET] /stores/{id}
     */
    public function show(string $id) {
        $store = Store::findOrFail($id);

        if ($this->canAccess(\Auth::id(), $store) == false) {
            return back()
                ->with("Auth Fail", "表示権限がありません");
        }

        return view("stores.show", [
            "store" => $store
        ]);
    }


    /**
     * [POST] /stores
     */
    public function store(Request $request) {
        // # バリデーション
        $this->validateStoreRequest($request);

        // # 認証済みユーザーが作成した store
        $store = $request->user()->stores()->create([
            "name" => $request->input("name"),
            "is_public" => $request->input("is_public") == '1'
        ]);

        return back();
    }

    
    /**
     * [GET] /stores/{id}/edit
     */
    public function edit(string $id)
    {
        // # 編集する store を取得
        $store = Store::findOrFail($id);

        // # 編集可能か確認
        if ($this->canAccess(\Auth::id(), $store) == false) {
            return back()
                ->with("Auth Fail", "表示権限がありません");
        }
        
        // # view を返す
        return view("stores.edit", [
            "store" => $store
        ]);
    }


    /**
     * [PUT] /stores/{id}
     */
    public function update(Request $request, string $id)
    {
        // # バリデーション
        $this->validateStoreRequest($request);

        // # 変更予定の値の取得
        $store = Store::findOrFail($id);
        $name = $request->input("name");
        $is_public = $request->input("is_public") == "1";

        // # 値の変更
        // ## name の変更
        if ($store->name != $name) {
            if ($this->canAccess(\Auth::id(), $store) == false) {
                return back()
                    ->with("Auth Fail", "編集権限がありません");
            }
            
            $store->name = $name;
        }
            
        // ## is_public の変更（作成者のみ変更可能）
        if ($store->is_public != $is_public) {
            if ($store->created_by != \Auth::id()) {
                return back()
                    ->with("Auth Fail", "編集権限がありません");
            }

            $store->is_public = $is_public;
        }
        
        // ## 変更の保存
        $store->save();

        return back();
    }


    /**
     * [DELETE] /stores/{id}
     */
    public function destroy(string $id)
    {
        $store = Store::findOrFail($id);

        // # 削除（作成者のみ削除可能）
        if ($store->created_by != \Auth::id()) {
            return back()
                ->with("Auth Fail", "削除権限がありません");
        }

        $store->delete();

        return back();
    }

    private function canAccess(int $userId, Store $store) 
    {
        return $store->is_public || $store->created_by == $userId;
    }

    private function validateStoreRequest(Request $request) {
        $request->validate([
            "name" => "required|max:255",
            "is_public" => "nullable|boolean"
        ]);
    }
}
