<?php
// # 初期化
use App\Models\User;
use App\Models\Store;

// # ユーザーを作成
$user1 = User::create([
    "name" => "test1",
    "email" => "examples.models.test1@example.com",
    "password" => \Hash::make("password")
]);

// # ユーザーが store を作成
$store = $user1->stores()->create([
    "name" => "test_store1",
    "is_public" => false
]);

// # store に record を作成
$store = Store::find(1);
$user = User::find(2);
$record = $store->records()->create([
    "index" => 1,
    "created_by" => $user->id
]);

// # store に propDefinition を作成
$store = Store::find(1);
$user = User::find(2);
$propDef = $store->propDefinitions()->create([
    "name" => "prop1",
    "index" => 1
]);

// # createdBy: User を取得
$store = Store::find(1);
$user = $store->createdBy; // User モデルのインスタンス
// created_by ではカラムの実値が返ってきてしまう
// もしスネークケースで判断つかない名 creator() で統一する場合、creator_id となるはず

// # record の propDefinition に値を入れる
$user = User::find(2);
$store = $user->stores()->first();
$propDef = $store->propDefinitions()->first();
$record = $store->records()->first();
$record->props()->attach($propDef->id, ["value" => "Hello, Stores!"]);