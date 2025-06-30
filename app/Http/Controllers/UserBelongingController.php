<?php

namespace App\Http\Controllers;

use App\Models\User;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserBelongingController extends Controller
{
    // POST user_group/{id}/members
    public function store(Request $request, string $id)
    {
        // # バリデーション
        $request->validate([
            "userId" => "sometimes",
            "email" => "sometimes",
            "isOwner" => "nullable"
        ]);

        $userGroupId = intval($id);
        $userId = $request->input("userId");
        $email = $request->input("email");
        $isOwner = $request->input("isOwner") == "on";

        if (isset($userId) == false && isset($email) == false) {
            throw new ErrorException("userId または email のどちらかが必要です");
        }

        // # ユーザーを取得
        $user = isset($userId)
            ? User::findOrFail(intval($userId))
            : User::where("email", $email)->first();

        if (isset($user) == false) {
            Log::info("[UserBelongingsController store] ユーザーが見つかりませんでした： { $userId / $email }");
            return back()->with("Fail", "user { $email } not found");
        }
        
        // # 自身がオーナーのグループに追加する
        $userGroup = $request->user()->ownedGroups()->findOrFail($userGroupId);

        $userGroup->users()->attach($user->id, [
            "is_owner" => $isOwner
        ]);

        return back();
    }

    // PUT user_group/{id}/members
    public function update(Request $request, string $id)
    {
        $request->validate([
            "userId" => "required",
            "isOwner" => "required"
        ]);

        $userGroupId = intval($id);
        $userId = intval($request->input("userId"));
        $isOwner = $request->input("isOwner") == "on";

        // # 自身がオーナーのグループを更新する
        $userGroup = $request->user()->ownedGroups()->find($userGroupId);
        
        if ($userGroup->createdBy->id == $userId && $isOwner == false) {
            return back()->with("Fail", "作成者はオーナーである必要があります");
        }
        $userGroup->users()->updateExistingPivot($userId, [
            "is_owner" => $isOwner
        ]);

        return redirect()->route("user_groups.show", $userGroupId);
    }

    // DELETE user_group/{id}/members
    public function destroy(Request $request, string $id)
    {
        $request->validate([
            "userId" => "required"
        ]);

        $userGroupId = intval($id);
        $userId = intval($request->input("userId"));

        // # 自身がオーナーのグループを更新する
        $userGroup = $request->user()->ownedGroups()->find($userGroupId);
        
        if ($userGroup->createdBy->id == $userId) {
            return back()->with("Fail", "作成者はメンバーから削除できません");
        }

        $userGroup->users()->detach($userId);

        return redirect()->route("user_groups.show", $userGroupId);
    }
}
