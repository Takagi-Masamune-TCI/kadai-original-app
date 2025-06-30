<?php

namespace App\Http\Controllers;

use App\Models\UserGroup;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    // POST /user_groups/create
    public function store(Request $request)
    {
        $this->validateUserGroupRequest($request);

        // # 作成する
        $userGroup = $request->user()->createdGroups()
            ->create([
                "name" => $request->input("name")
            ]);
        
        // # メンバーに作成者を入れ、かつオーナーにする
        $userGroup->users()->attach(\Auth::id(), [
            "is_owner" => true
        ]);

        return back();
    }

    // GET /user_groups/{id}
    public function show(Request $request, string $id)
    {
        $userGroup = $request->user()->groups()->findOrFail(intval($id));
        
        return view("user_groups.show", [
            "userGroup" => $userGroup
        ]);
    }

    // GET /user_groups/{id}/edits
    public function edit(Request $request, string $id)
    {
        $userGroup = $request->user()->groups()->findOrFail(intval($id));
        
        return view("user_groups.edit", [
            "userGroup" => $userGroup
        ]);
    }

    // PUT /user_groups/{id}
    public function update(Request $request, string $id)
    {
        // # バリデーション
        $this->validateUserGroupRequest($request);

        // # モデルの取得
        $userGroup = $request->user()->groups()->findOrFail(intval($id));

        // # 値の変更
        $userGroup->name = $request->input("name");

        // # 保存
        $userGroup->save();

        return redirect()->route("user_groups.show", $id);
    }

    // DELETE /user_groups/{id}
    public function destroy(Request $request, string $id)
    {
        $userGroup = $request->user()->groups()->findOrFail(intval($id));
        $userGroup->delete();

        return redirect()->route("profile.show");
    }

    private function validateUserGroupRequest(Request $request)
    {
        $request->validate([
            "name" => "nullable"
        ]);
    }
}
