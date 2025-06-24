<?php

namespace App\Http\Controllers;

use App\Models\PropDefinition;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $is_public = $request->input("is_public") == "on";
        
        Log::info("[Debug] $store->is_public -> $is_public");

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

        // ## propDefinitions の変更
        $propDefs = [];
        // ### 新しい要素の作成・既存の要素の更新
        foreach ($request->input("propDefinition_id") ?? [] as $i => $propId) {
            $propName = $request->input("propDefinition_name")[$i];
            if ($propId == "new") {
                $index = $store->propDefinitions()->max("index") + 1;
                $propDefs[] = $store->propDefinitions()->create([
                    "name" => $propName,
                    "index" => $index
                ]);
            } else {
                $propDef = $store->propDefinitions()->findOrFail($propId);
                $propDef->name = $propName;
                $propDefs[] = $propDef;
            }
        }
        // ### 指定されていない要素を削除
        $store->propDefinitions()
            ->whereNotIn("id", array_map(fn ($propDef) => $propDef->id, $propDefs))
            ->delete();

        // ### 適用可能な index を順に並べる
        $indexes = array_map(fn ($propDef) => $propDef->index, $propDefs);
        sort($indexes, SORT_NUMERIC);

        
        // ### 適用可能な index に、リクエスト順に割り当て、保存する
        foreach ($propDefs ?? [] as $i => $propDef) {
            // 前から順に小さい物からつけていく
            $propDef->index = $indexes[$i];
            $propDef->save();
        }
        
        // ## 変更の保存
        $store->save();

        return redirect("/stores/{$store->id}");
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

    private function canAccess(int|null $userId, Store $store) 
    {
        return $store->is_public || $store->created_by == $userId;
    }

    private function validateStoreRequest(Request $request) {
        $request->validate([
            "name" => "max:255",
            // "is_public" => "nullable|boolean"
        ]);
    }
}
