<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        // # データ定義
        $storeDataset = [
            "人物" => [
                "is_public" => false,
                "created_by" => $user1,
                "favorited_by" => [$user1],
                "propNames" => ["名前", "読み仮名", "メールアドレス", "生年月日", "出身"],
                "records" => [
                    [ "created_by" => $user1, "髙木 正宗", "たかぎ まさむね", "Takagi.Masamune@trans-cosmos.co.jp", "2001.09/03", "茨城県土浦市\n埼玉県さいたま市見沼区\n東京都江東区\n東京都北区", "favorited_by" => [$user1] ],
                    [ "created_by" => $user1, "鈴木 太郎", "すずき たろう",   "Suzuki.Taro@example.com",            "2002.04/02", "千葉県" ],
                    [ "created_by" => $user1, "佐藤 花子", "さとう はなこ",   "Sato.Hanako@example.com",            "2002.05/03", "埼玉県" ],
                ]
            ],
            "日記" => [
                "is_public" => false,
                "created_by" => $user1,
                "favorited_by" => [$user1],
                "propNames" => ["日時", "感情", "場所", "やったこと"],
                "records" => [
                    [ "created_by" => $user1, "2025.06/20  9:00-17:50", "渋谷FT 7階", "業務"],
                    [ "created_by" => $user1, "2025.06/20 19:00-21:00", "365酒場", "飲み会", 
                        "favorited_by" => [$user1]
                    ],
                    [ "created_by" => $user1, "2025.06/20 21:30-23:30", "ダーツバー", "飲み会", 
                        "favorited_by" => [$user1]
                    ],
                    [ "created_by" => $user1, "2025.06/20 24:00-29:00", "まねきねこ", "カラオケ", 
                        "favorited_by" => [$user1]
                    ],

                    [ "created_by" => $user1, "2025.06/21 16:00-19:00", "木場 → 辰巳 → 東雲 → 有明", "散歩", 
                        "favorited_by" => [$user1]
                    ],
                    [ "created_by" => $user1, "2025.06/21 19:00-22:00", "有明ガーデン", "ショッピング・夕飯", 
                        "favorited_by" => [$user1]
                    ],
                    [ "created_by" => $user1, "2025.06/21 22:00-23:00", "有明 → 豊洲 → 枝川 → 木場", "散歩", 
                        "favorited_by" => [$user1]
                    ],
                    
                    [ "created_by" => $user1, "2025.06/22 18:00-19:30", "イトーヨーカドー木場", "買い物"],
                    
                    [ "created_by" => $user1, "2025.06/23  9:00-17:50", "自宅", "業務"],
                    [ "created_by" => $user1, "2025.06/23 18:10-19:30", "田端 → 東大前 → 御徒町", "散歩"],
                    [ "created_by" => $user1, "2025.06/23 20:00-21:30", "ガスト 田端店", "夕飯"],
                    
                    [ "created_by" => $user1, "2025.06/24  9:00-17:50", "自宅", "業務"],
                ]
            ],
            "場所" => [
                "is_public" => true,
                "created_by" => $user1,
                "favorited_by" => [],
                "propNames" => ["名称", "住所", "URL"],
                "records" => [
                    [ "created_by" => $user1, "渋谷ファーストタワー", "東京都 渋谷区 東1丁目 2-20", 
                        "favorited_by" => [$user1]
                    ],
                    [ "created_by" => $user2, "渋谷駅", "東京都 渋谷区 道玄坂1丁目 1"],
                    [ "created_by" => $user1, "東急プラザ渋谷", "東京都 渋谷区 道玄坂1丁目 2-3 渋谷フクラス", "Google Map\nhttps://www.google.com/maps/place/%E6%9D%B1%E6%80%A5%E3%83%97%E3%83%A9%E3%82%B6%E6%B8%8B%E8%B0%B7/data=!4m2!3m1!1s0x0:0xbd5b31da1618069e?sa=X&ved=1t:2428&ictx=111"],
                    [ "created_by" => $user2, "自宅", "東京都 世田谷区 ○○ ○-○-○"],
                ]
            ],
            "タスク" => [
                "is_public" => true,
                "created_by" => $user1,
                "favorited_by" => [$user1],
                "propNames" => ["名称", "状態", "期限", "重要度"],
                "records" => [
                    [ "created_by" => $user1, "2025年度デジタルスキル研修 受講", "未完了", "2025.09/30", "高", 
                        "favorited_by" => [$user1]
                    ]
                ]
            ],
            "イベント" => [
                "is_public" => true,
                "created_by" => $user2,
                "favorited_by" => [$user1],
                "propNames" => ["名称", "日付・期間", "場所", "内容"],
                "records" => [
                    [ "created_by" => $user1, "HOKUSAI：ANOTHER STORY in TOKYO", "2025.2/1(土)～8/11(月)", "東急プラザ渋谷", "最先端イマーシブ体験で葛飾北斎の世界に没入\nhttps://hokusai.anotherstory.world/", 
                        "favorited_by" => [$user1]
                    ]
                ]
            ],
            "飲食店" => [
                "is_public" => true,
                "created_by" => $user2,
                "favorited_by" => [],
                "propNames" => ["名称", "タイプ", "場所", "メニュー", "レビュー"],
                "records" => [
                    [ "created_by" => $user1, "トランスレストラン", "イタリアンレストラン", "東京都 渋谷区", "スパゲティ他", "3.65", 
                        "favorited_by" => [$user1]
                    ]
                ]
            ],
            "レシピ" => [
                "is_public" => true,
                "created_by" => $user2,
                "favorited_by" => [],
                "propNames" => ["名称", "カテゴリ", "時間", "材料", "手順"],
                "records" => [
                    [ "created_by" => $user1, "炙りカルボナーラ", "スパゲティ メイン", "15分", "スパゲティ125g\n塩10g\n卵2個\n粉チーズ40g\nベーコン6本\nミックスチーズ30g", "1. カルボナーラを作る。\n2. ミックスチーズをのせて炙る。", 
                        "favorited_by" => [$user1]
                    ]
                ]
            ],
            "映画作品" => [
                "is_public" => true,
                "created_by" => $user2,
                "favorited_by" => [],
                "propNames" => ["名称", "カテゴリ", "上映時間", "上映時期", "レビュー"],
                "records" => [
                    [ "created_by" => $user1, "君の名は。", "ファンタジー 青春 アニメ", "110分", "2016年", "衝撃的だった。", 
                        "favorited_by" => [$user1]
                    ]
                ]
            ],
        ];


        // # データ入力
        foreach ($storeDataset as $storeName => $storeData) {
            // ## Store の作成
            $store = $storeData["created_by"]->stores()->create([
                "name" => $storeName,
                "is_public" => $storeData["is_public"]
            ]);
            
            // ## PropDefinition の追加
            $propDefs = [];
            foreach ($storeData["propNames"] as $pi => $name) {
                // 作成した propDefinition を保持しておく
                $propDefs[$name] = $store->propDefinitions()->create([
                    "name" => $name,
                    "index" => $pi
                ]);
            }

            // ## Record の追加
            foreach($storeData["records"] as $ri => $recordData) {
                // ### Record の作成
                $record = $store->records()->create([
                    "index" => $ri,
                    "created_by" => $recordData["created_by"]->id
                ]);

                // ### Record へ値の追加
                foreach ($storeData["propNames"] as $pi => $propName) {
                    // データがある場合のみ作成
                    if (isset($recordData[$pi])) {
                        $record->props()->attach($propDefs[$propName]->id, [ "value" => $recordData[$pi] ]);
                    }
                }

                // ### お気に入りの追加
                foreach ($recordData["favorited_by"] ?? [] as $user) {
                    $record->favoritedBy()->attach($user->id);
                }
            }

            // ## お気に入りの追加
            foreach ($storeData["favorited_by"] ?? [] as $user) {
                $store->favoritedBy()->attach($user->id);
            }
        }
    }
}
