<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Record;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    use StoreMemberAccessor, IndexManager;

    /**
     * [POST] /records
     */
    public function store(Request $request) 
    {
        // # バリデーション
        $this->validateStoreRequest($request);

        // # 認証済みユーザーが作成する record として作成

        // ## store へ追加可能か確認する
        $storeId = $request->input("store_id");
        $store = Store::find($storeId);

        if ($this->canAccessToStore(\Auth::id(), $store) == false) {
            return back()
                ->with("Auth Fail", "この Store に対するレコードの作成権限がありません");
        }

        // ## index の更新処理を行う
        $index = $request->input("index");
        if (isset($index)) {
            $this->reindexForInsertInto($store->records(), $index);
        } else {
            $index = Store::max("index") + 1;
        }

        // ## record を作成
        $record = $request->user()->records()->create([
            "store_id" => $store->id,
            "index" => $index
        ]);

        return back();
    }


    /**
     * [GET] /records/{id}/edit
     */
    public function edit(string $id) 
    {
        // # 編集する record を取得
        $record = Record::findOrFail($id);

        // # 編集可能か確認
        if ($this->canAccess(\Auth::id(), $record) == false) {
            return back()
                ->with("Auth Fail", "表示権限がありません");
        }
        
        // # view を返す
        return view("records.edit", [
            "record" => $record
        ]);
    }


    /**
     * [PUT] /records/{id}
     */
    public function update(Request $request, string $id) 
    {
        // # バリデーション
        $this->validateStoreRequest($request);
        $request->validate([
            "index" => "required"
        ]);

        // # 変更予定の値の取得
        $record = Record::findOrFail($id);
        $store = $record->store;
        $index = $request->input("index");
        $propIds = $request->input("propIds[]");
        $values = $request->input("values[]");

        // # 値の変更
        
        // ## index の更新処理
        if ($index != $record->index) {
            // 並び替えにはそのレコードへアクセスできる必要がある
            if ($this->canAccess(\Auth::id(), $record) == false) {
                return back()
                    ->with("Auth Fail", "編集権限がありません");
            }
            $this->reindexForReplace($store->records(), $record->index, $index);
            $record->index = $index;
        }

        // ## record の保存
        $record->save();

        // ## recordValue の保存
        foreach ($propIds as $i => $propId) {
            $value = $values[$i];
            $isPropExisting = $record->props()->where("prop_id", $propId)->exists();
            
            if ($isPropExisting) {
                // 既に存在していた場合、更新する
                $record->props()->updateExistingPivot($propId, ["value" => $value]);
            } else {
                // 対象の prop とのピボットに値を作成する
                $record->props()->attach($propId, ["value" => $value]);
            }
        }

        return back();
    }
    

    /**
     * [DELETE] /records/{id}
     */
    public function destroy(string $id) 
    {
        $record = Record::findOrFail($id);
        $store = $record->store;

        // # 削除可能か確認（作成者のみ削除可能）
        if ($record->created_by != \Auth::id()) {
            return back()
                ->with("Auth Fail", "削除権限がありません");
        }

        // # 他の record の index を変更
        $this->reindexForRemoveFrom($store->records(), $record->index);

        // # record を削除
        $record->delete();

        return back();
    }

    private function validateRecordRequest(Request $request) 
    {
        $request->validate([
            "index" => "nullable|number"
        ]);
    }

    private function canAccess(int $userId, Record $record)
    {
        $store = $record->store()->first();
        return $this->canAccessToStore($userId, $store);
    }
}
