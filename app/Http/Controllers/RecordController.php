<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecordController extends Controller
{
    use StoreMemberAccessor, IndexManager;

    /**
     * [POST] /records
     */
    public function store(Request $request) 
    {
        // # バリデーション
        $this->validateRecordRequest($request);
        $request->validate([
            "store_id" => "required"
        ]);

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
            $index = Record::max("index") + 1;
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
        $this->validateRecordRequest($request);
        $request->validate([
            "index" => "sometimes|numeric",
            "prop_ids" => "sometimes",
            "prop_values" => "sometimes",
            "with_props" => "required|boolean"
        ]);

        // # 変更予定の値の取得
        $record = Record::findOrFail($id);
        $store = $record->store;
        
        $index = $request->input("index");
        $withProps = $request->input("with_props") == true;
        $propIds = $request->input("prop_ids");
        $propValues = $request->input("prop_values");

        // # 値の変更
        $isUpdated = false;

        // ## index の更新処理
        Log::info("index: " . isset($index) . " " . $record->index . " -> " . $index);
        if (isset($index) && $index != $record->index) {
            // 並び替えにはそのレコードへアクセスできる必要がある
            if ($this->canAccess(\Auth::id(), $record) == false) {
                return back()
                    ->with("Auth Fail", "編集権限がありません");
            }
            $this->reindexForReplace($store->records(), $record->index, $index);
            $record->index = $index;
            $isUpdated = true;
        }

        // ## record の保存
        if ($isUpdated)
            $record->save();

        // ## recordValue の保存
        if ($withProps) {
            foreach ($propIds ?? [] as $i => $propId) {
                $value = $propValues[$i];
                $isPropExisting = $record->props()->where("prop_id", $propId)->exists();
                
                if ($isPropExisting) {
                    // 既に存在していた場合、更新する
                    $record->props()->updateExistingPivot($propId, ["value" => $value]);
                } else {
                    // 対象の prop とのピボットに値を作成する
                    if (isset($value) == false) {
                        // 更新するデータが無いためプロパティを作成しない
                        continue;
                    }
                    $record->props()->attach($propId, ["value" => $value]);
                }
            }
        }

        return redirect("/stores/$store->id");
    }

    /**
     * [POST] /records/{id}/insert
     */
    public function insert(Request $request, string $id) 
    {
        // # 挿入先を取得
        $request->validate([
            "into_id" => "required|numeric"
        ]);
        $into = Record::findOrFail(intval($request->input("into_id")));

        // # 挿入するレコードを取得
        $record = Record::findOrFail(intval($id));

        if ($record->id != $into->id) {
            // # レコードを挿入先へ挿入
            $this->reindexForReplace($record->store->records(), $record->index, $into->index);
            $record->index = $into->index;
            $record->save();
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
            "index" => "nullable|numeric"
        ]);
    }

    private function canAccess(int $userId, Record $record)
    {
        $store = $record->store()->first();
        return $this->canAccessToStore($userId, $store);
    }
}
