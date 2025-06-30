<?php

/*
sudo ./vendor/bin/sail artisan migrate:reset;sudo ./vendor/bin/sail artisan migrate;sudo ./vendor/bin/sail artisan db:seed;
*/

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_masamune = User::where("email", "Takagi.Masamune@example.com")->first();
        $user_suzuo = User::where("email", "Toran.Suzuo@example.com")->first();
        $user_sumiko = User::where("email", "Kosumo.Sumiko@example.com")->first();
        $user_oyamune = User::where("email", "Takagi.Oyamune@example.com")->first();
        $user1 = User::where("email", "user1@example.com")->first();
        $user2 = User::where("email", "user2@example.com")->first();
        $user3 = User::where("email", "user3@example.com")->first();
        $user4 = User::where("email", "user4@example.com")->first();
        $user5 = User::where("email", "user5@example.com")->first();

        // # グループの作成
        $userGroup_TCI_DI = UserGroup::create([
            "name" => "トランスコスモス DI事業本部",
            "created_by" => $user_suzuo->id
        ]);
        $user_suzuo->groups()->attach($userGroup_TCI_DI->id, [
            "is_owner" => true
        ]);

        // # メンバーの登録
        $userGroup_TCI_DI->users()->attach($user_masamune->id, [
            "is_owner" => false
        ]);
        
        $userGroup_TCI_DI->users()->attach($user_sumiko->id, [
            "is_owner" => false
        ]);
        
        $userGroup_TCI_DI->users()->attach($user1->id, [
            "is_owner" => false
        ]);

        $userGroup_TCI_DI->users()->attach($user2->id, [
            "is_owner" => false
        ]);

        $userGroup_TCI_DI->users()->attach($user3->id, [
            "is_owner" => false
        ]);

        $userGroup_TCI_DI->users()->attach($user4->id, [
            "is_owner" => false
        ]);

        // # グループの作成
        $userGroup_TCI_DI_25 = UserGroup::create([
            "name" => "TCI DI事業本部 25新卒",
            "created_by" => $user_suzuo->id
        ]);
        $user_suzuo->groups()->attach($userGroup_TCI_DI_25->id, [
            "is_owner" => true
        ]);

        // # メンバーの登録
        $userGroup_TCI_DI_25->users()->attach($user_masamune->id, [
            "is_owner" => true
        ]);

        $userGroup_TCI_DI_25->users()->attach($user_sumiko->id, [
            "is_owner" => true
        ]);

        $userGroup_TCI_DI_25->users()->attach($user2->id, [
            "is_owner" => false
        ]);

        $userGroup_TCI_DI_25->users()->attach($user4->id, [
            "is_owner" => false
        ]);

        // # グループの作成
        $userGroup_TCI_DI_25 = UserGroup::create([
            "name" => "髙木家",
            "created_by" => $user_oyamune->id
        ]);
        $user_oyamune->groups()->attach($userGroup_TCI_DI_25->id, [
            "is_owner" => true
        ]);

        // # メンバーの登録
        $userGroup_TCI_DI_25->users()->attach($user_masamune->id, [
            "is_owner" => true
        ]);


    }
}
