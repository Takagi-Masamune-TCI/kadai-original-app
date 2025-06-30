<?php

namespace App\Http\Controllers;

use App\Models\PropDefinition;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class PropDefinitionController extends Controller 
{
    use StoreMemberAccessor, IndexManager;

    /**
     * [POST] /prop_definitions
     */
    public function store(Request $request)
    {
        // # バリデーション
        $this->validatePropDefinitionRequest($request);

        // # 認証済みユーザーが作成する propDefinition として作成

        // ## store へ追加可能か確認する
        $storeId = $request->input("store_id");
        $store = Store::find($storeId);

        if ($this->canAccessToStore(\Auth::user(), $store) == false) {
            if (\Auth::check()) {
                return back()
                    ->with("Auth Fail", "この Store に対するレコードの作成権限がありません");
            } else {
                return redirect("login");
            }
        }

        // ## index の更新処理を行う
        $index = $request->input("index");
        if (isset($index)) {
            $this->reindexForInsertInto($store->propDefinitions(), $index);
        } else {
            $index = PropDefinition::max("index") + 1;
        }

        // ## propDefinition を作成
        $name = $request->input("name");
        $propDefinition = $request->user()->records()->create([
            "name" => $name,
            "index" => $index,
            "store_id" => $store->id
        ]);

        return back();
    }

    /**
     * [PUT] /prop_definitions/{id}
     */
    public function update(Request $request, $id)
    {
        // # バリデーション
        $this->validateStoreRequest($request);
        $request->validate([
            "index" => "required"
        ]);

        // # 変更予定の値の取得
        $propDefinition = PropDefinition::findOrFail($id);
        $store = $propDefinition->store;
        $name = $request->input("name");
        $index = $request->input("index");

        // # 値の変更
        
        // ## index の更新処理
        if ($index != $propDefinition->index) {
            // 並び替えにはその propDefinition へアクセスできる必要がある
            if ($this->canAccess(\Auth::user(), $propDefinition) == false) {
                if (\Auth::check()) {
                    return back()
                        ->with("Auth Fail", "編集権限がありません");
                } else {
                    return redirect("login");
                }
            }
            $this->reindexForReplace($store->propDefinitions(), $propDefinition->index, $index);
            $propDefinition->index = $index;
        }

        // ## propDefinition の保存
        $propDefinition->save();

        return back();
    }

    /**
     * [DELETE] /prop_definitions/{id}
     */
    public function destroy(string $id)
    {
        $propDefinition = PropDefinition::findOrFail($id);
        $store = $propDefinition->store;

        // # 削除可能か確認（作成者のみ削除可能）
        if ($propDefinition->created_by != \Auth::id()) {
            if (\Auth::check()) {
                return back()
                    ->with("Auth Fail", "削除権限がありません");
            } else {
                return redirect("login");
            }
        }

        // # 他の propDefinition の index を変更
        $this->reindexForRemoveFrom($store->propDefinitions(), $propDefinition->index);

        // # propDefinition を削除
        $propDefinition->delete();

        return back();
    }

    private function validatePropDefinitionRequest(Request $request)
    {
        $request->validate([
            "name" => "required|max:255",
            "index" => "nullable|numeric"
        ]);
    }

    private function canAccess(User $user, PropDefinition $propDefinition)
    {
        
        $store = $propDefinition->store()->first();
        return $this->canAccessToStore($user, $store);
    }
}
