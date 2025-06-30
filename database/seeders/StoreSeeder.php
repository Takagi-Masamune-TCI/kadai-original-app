<?php

/*
sudo ./vendor/bin/sail artisan migrate:reset;sudo ./vendor/bin/sail artisan migrate;sudo ./vendor/bin/sail artisan db:seed;
*/

namespace Database\Seeders;

use App\Models\Record;
use App\Models\Store;
use App\Models\User;
use App\Models\UserGroup;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_masamune = User::where("email", "Takagi.Masamune@example.com")->firstOrFail();
        $user_oyamune = User::where("email", "Takagi.Oyamune@example.com")->firstOrFail();
        $user_suzuo = User::where("email", "Toran.Suzuo@example.com")->firstOrFail();
        $user_sumiko = User::where("email", "Kosumo.Sumiko@example.com")->firstOrFail();

        $userGroup_TCI_DI = UserGroup::where("name", "トランスコスモス DI事業本部")->firstOrFail();
        $userGroup_TCI_DI_25 = UserGroup::where("name", "TCI DI事業本部 25新卒")->firstOrFail();
        $userGroup_tkg = UserGroup::where("name", "髙木家")->firstOrFail();

        // # データ定義
        $storeDataset = [
            "人物" => [
                "isPublic" => false,
                "createdBy" => $user_masamune,
                "userGroups" => [],
                "favoritedBy" => [],
                "propNames" => ["名前", "読み仮名", "メールアドレス", "電話番号", "生年月日", "出身"],
                "records" => [
                    [ "createdBy" => $user_masamune, "髙木 正宗", "たかぎ まさむね", "Takagi.Masamune@trans-cosmos.co.jp", "070-XXXX-XXXX", "2001.09/03", "茨城県土浦市\n埼玉県さいたま市見沼区\n東京都江東区\n東京都北区", "favoritedBy" => [$user_masamune] ],
                    [ "createdBy" => $user_masamune, "髙木 親宗", "たかぎ おやむね", "Takagi.Oyamune@example.com",         "090-XXXX-XXXX", "1960.04/02", "茨城県つくば市" ],
                    [ "createdBy" => $user_masamune, "戸蘭 鈴雄", "とらん すずお",   "Toran.Suzuo@example.com",            "",              "",           "千葉県" ],
                    [ "createdBy" => $user_masamune, "越雲 澄子", "こすも すみこ",   "Kosumo.Sumiko@example.com",          "080-XXXX-XXXX", "",           "埼玉県" ],
                ]
            ],
            "日記" => [
                "isPublic" => false,
                "createdBy" => $user_masamune,
                "userGroups" => [],
                "favoritedBy" => [$user_masamune],
                "propNames" => ["日時", "感情", "場所", "やったこと"],
                "records" => [
                    [ "createdBy" => $user_masamune, "2025.06/20  9:00-17:50", "渋谷FT 7階", "業務"],
                    [ "createdBy" => $user_masamune, "2025.06/20 19:00-21:00", "365酒場", "飲み会", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    [ "createdBy" => $user_masamune, "2025.06/20 21:30-23:30", "ダーツバー", "飲み会", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    [ "createdBy" => $user_masamune, "2025.06/20 24:00-29:00", "まねきねこ", "カラオケ", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    [ "createdBy" => $user_masamune, "2025.06/21 6:00-12:00", "自宅", "睡眠", 
                        "favoritedBy" => []
                    ],

                    [ "createdBy" => $user_masamune, "2025.06/21 16:00-19:00", "木場 → 辰巳 → 東雲 → 有明", "散歩", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    [ "createdBy" => $user_masamune, "2025.06/21 19:00-22:00", "有明ガーデン", "ショッピング・夕飯", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    [ "createdBy" => $user_masamune, "2025.06/21 22:00-23:00", "有明 → 豊洲 → 枝川 → 木場", "散歩", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    
                    [ "createdBy" => $user_masamune, "2025.06/22 18:00-19:30", "イトーヨーカドー木場", "買い物"],
                    
                    [ "createdBy" => $user_masamune, "2025.06/23  9:00-17:50", "自宅", "業務"],
                    [ "createdBy" => $user_masamune, "2025.06/23 18:10-19:30", "田端 → 東大前 → 御徒町", "散歩"],
                    [ "createdBy" => $user_masamune, "2025.06/23 20:00-21:30", "ガスト 田端店", "夕飯"],
                    
                    [ "createdBy" => $user_masamune, "2025.06/24  9:00-17:50", "自宅", "業務"],

                    [ "createdBy" => $user_masamune, "2025.06/25  9:00-17:50", "自宅", "業務"],
                    [ "createdBy" => $user_masamune, "2025.06/25 18:10-19:30", "自宅", "ITパスポート試験勉強"],

                    [ "createdBy" => $user_masamune, "2025.06/26  9:00-17:50", "自宅", "業務"],
                    [ "createdBy" => $user_masamune, "2025.06/26 18:10-19:30", "小松庵総本家 駒込本店", "夕飯（桜そば）"],

                    [ "createdBy" => $user_masamune, "2025.06/27  9:00-17:50", "渋谷FT", "業務"],
                ]
            ],
            "場所" => [
                "isPublic" => true,
                "createdBy" => $user_suzuo,
                "userGroups" => [],
                "favoritedBy" => [],
                "propNames" => ["名称", "住所", "URL"],
                "records" => [
                    [ "createdBy" => $user_masamune, "渋谷ファーストタワー", "東京都 渋谷区 東1丁目 2-20", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    [ "createdBy" => $user_suzuo, "渋谷駅", "東京都 渋谷区 道玄坂1丁目 1"],
                    [ "createdBy" => $user_masamune, "東急プラザ渋谷", "東京都 渋谷区 道玄坂1丁目 2-3 渋谷フクラス", "Google Map\nhttps://www.google.com/maps/place/%E6%9D%B1%E6%80%A5%E3%83%97%E3%83%A9%E3%82%B6%E6%B8%8B%E8%B0%B7/data=!4m2!3m1!1s0x0:0xbd5b31da1618069e?sa=X&ved=1t:2428&ictx=111"],
                    [ "createdBy" => $user_suzuo, "自宅", "東京都 世田谷区 ○○ ○-○-○"],
                ]
            ],
            "タスク" => [
                "isPublic" => false,
                "createdBy" => $user_masamune,
                "userGroups" => [],
                "favoritedBy" => [],
                "propNames" => ["名称", "状態", "期限", "重要度"],
                "records" => [
                    [ "createdBy" => $user_masamune, "2025年度デジタルスキル研修 受講", "未完了", "2025.09/30", "高", 
                        "favoritedBy" => [$user_masamune]
                    ]
                ]
            ],
            "イベント" => [
                "isPublic" => true,
                "createdBy" => $user_masamune,
                "userGroups" => [],
                "favoritedBy" => [$user_masamune],
                "propNames" => ["名称", "カテゴリ", "日付・期間", "場所", "内容"],
                "records" => [
                    [ "createdBy" => $user_masamune, "HOKUSAI：ANOTHER STORY in TOKYO", "芸術展", "2025.2/1(土)～8/11(月)", "東急プラザ渋谷", "最先端イマーシブ体験で葛飾北斎の世界に没入\nhttps://hokusai.anotherstory.world/", 
                        "favoritedBy" => [$user_masamune]
                    ]
                ]
            ],
            "飲食店" => [
                "isPublic" => true,
                "createdBy" => $user_sumiko,
                "userGroups" => [],
                "favoritedBy" => [$user_masamune],
                "propNames" => ["名称", "タイプ", "場所", "メニュー", "レビュー"],
                "records" => [
                    [ "createdBy" => $user_masamune, "トランスレストラン", "イタリアンレストラン", "東京都 渋谷区", "スパゲティ他", "3.65", 
                        "favoritedBy" => [$user_masamune]
                    ]
                ]
            ],
            "社内イベント" => [
                "isPublic" => false,
                "createdBy" => $user_suzuo,
                "userGroups" => [$userGroup_TCI_DI],
                "favoritedBy" => [],
                "propNames" => ["名称", "カテゴリ", "日時", "場所"],
                "records" => [
                    [ "createdBy" => $user_masamune, "2025年度下期", "統括部長講話", "2025.08/29", "渋谷FT 7階", 
                        "favoritedBy" => [$user_masamune]
                    ]
                ]
            ],
            "社員" => [
                "isPublic" => false,
                "createdBy" => $user_suzuo,
                "userGroups" => [$userGroup_TCI_DI],
                "favoritedBy" => [],
                "propNames" => ["名前", "読み方", "入社時期", "拠点", "部署"],
                "records" => [
                    [ "createdBy" => $user_masamune, "髙木 正宗", "たかぎ まさむね", "2025年4月", "渋谷", "MT統括SS1課",
                        "favoritedBy" => [$user_masamune]
                    ]
                ]
            ],
            "社内SNS" => [
                "isPublic" => false,
                "createdBy" => $user_suzuo,
                "userGroups" => [$userGroup_TCI_DI],
                "favoritedBy" => [],
                "propNames" => ["本文"],
                "records" => [
                    [ "createdBy" => $user_suzuo, "イントラネットにて○○の社販が利用できるようになってました！アツい！", 
                        "favoritedBy" => [$user_suzuo]
                    ],
                    [ "createdBy" => $user_suzuo, "今週のDMMドリル間違えた……AI関連しっかり復習しないとな……",
                        "favoritedBy" => [$user_suzuo]
                    ],
                    [ "createdBy" => $user_suzuo, "",
                        "favoritedBy" => [$user_suzuo]
                    ]
                ]
            ],
            "25新卒 社内SNS" => [
                "isPublic" => false,
                "createdBy" => $user_masamune,
                "userGroups" => [$userGroup_TCI_DI_25],
                "favoritedBy" => [$user_masamune],
                "propNames" => ["本文"],
                "records" => [
                    [ "createdBy" => $user_masamune, "お昼。セブンのひもかわうどん、この時期冷たいし喉越しいいし美味しくておすすめ。", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    [ "createdBy" => $user_masamune, "今日の業務終わり。後のEクラス飲み楽しみ！", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    [ "createdBy" => $user_masamune, "眠すぎるから5分だけ休憩しよ", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    [ "createdBy" => $user_masamune, "Eクラス飲み会いいな。僕たちも企画しようかな……", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    [ "createdBy" => $user_masamune, "今日の業務後渋谷で飲める人募集", 
                        "favoritedBy" => [$user_masamune]
                    ],
                    [ "createdBy" => $user_masamune, "エルダーさんがいい人過ぎる", 
                        "favoritedBy" => [$user_masamune]
                    ]
                ]
            ],
            "25新卒 用語Wiki" => [
                "isPublic" => false,
                "createdBy" => $user_sumiko,
                "userGroups" => [$userGroup_TCI_DI_25],
                "favoritedBy" => [],
                "propNames" => ["用語", "説明"],
                "records" => [
                    [ "createdBy" => $user_sumiko, "CMS", "Webサイトのコンテンツなどを格納するデータベースを、エンドユーザーも使い易くし、更新・管理しやすくしたもの。", 
                        "favoritedBy" => [$user_sumiko]
                    ],
                    [ "createdBy" => $user_sumiko, "HTML", "HyperText Markup Languageの略。ウェブページを作成するためのマークアップ言語。", 
                        "favoritedBy" => [$user_sumiko]
                    ],
                    [ "createdBy" => $user_sumiko, "CSS", "Cascading Style Sheetsの略。HTMLで作成されたページのスタイルを定義するための言語。", 
                        "favoritedBy" => [$user_sumiko]
                    ],
                    [ "createdBy" => $user_sumiko, "JavaScript ", " ウェブページに動的な機能を追加するためのプログラミング言語。", 
                        "favoritedBy" => [$user_sumiko]
                    ],
                    [ "createdBy" => $user_sumiko, "API", "Application Programming Interfaceの略。他のソフトウェアと連携するためのインターフェース。", 
                        "favoritedBy" => [$user_sumiko]
                    ],
                    [ "createdBy" => $user_sumiko, "trans-Re:Connect", "BP部が開発している、LINEミニアプリなどでのアプリケーションの基盤。", 
                        "favoritedBy" => [$user_sumiko]
                    ],
                    [ "createdBy" => $user_sumiko, "trans-CMS", "CMSS部が開発しているCMSプラットフォーム。", 
                        "favoritedBy" => [$user_sumiko]
                    ],
                    [ "createdBy" => $user_sumiko, "SEO", "Search Engine Optimizationの略。検索エンジン最適化。", 
                        "favoritedBy" => [$user_sumiko]
                    ],
                ]
            ],
            "買い物リスト" => [
                "isPublic" => false,
                "createdBy" => $user_oyamune,
                "userGroups" => [$userGroup_tkg],
                "favoritedBy" => [$user_masamune],
                "propNames" => ["名称", "状態", "場所"],
                "records" => [
                    [ "createdBy" => $user_oyamune, "卵10個入り", "未完了", "生協",
                        "favoritedBy" => [$user_oyamune]
                    ],
                    [ "createdBy" => $user_oyamune, "牛乳2本", "未完了", "生協",
                        "favoritedBy" => [$user_oyamune]
                    ],
                    [ "createdBy" => $user_oyamune, "トイレットペーパー", "未完了", "ドラッグストア", 
                        "favoritedBy" => [$user_oyamune]
                    ],
                    [ "createdBy" => $user_oyamune, "大根", "未完了", "生協",
                        "favoritedBy" => [$user_oyamune]
                    ],
                    [ "createdBy" => $user_oyamune, "大根", "未完了", "生協",
                        "favoritedBy" => [$user_oyamune]
                    ],
                    [ "createdBy" => $user_oyamune, "ペーパータオル", "未完了", "生協",
                        "favoritedBy" => [$user_oyamune]
                    ],
                ]
            ],
            "髙木家掲示板" => [
                "isPublic" => false,
                "createdBy" => $user_oyamune,
                "userGroups" => [$userGroup_tkg],
                "favoritedBy" => [],
                "propNames" => ["タイトル", "内容"],
                "records" => [
                    [ "createdBy" => $user_oyamune, "6/27(金)出勤", "", 
                        "favoritedBy" => [$user_oyamune]
                    ],
                ]
            ],
            "スケジュール" => [
                "isPublic" => false,
                "createdBy" => $user_sumiko,
                "userGroups" => [],
                "favoritedBy" => [],
                "propNames" => ["名称", "カテゴリ", "日時", "場所"],
                "records" => [
                    [ "createdBy" => $user_sumiko, "出社", "出社", "2025.06/27", "渋谷FT", 
                        "favoritedBy" => [$user_sumiko]
                    ]
                ]
            ],
            "映画作品" => [
                "isPublic" => true,
                "createdBy" => $user_sumiko,
                "userGroups" => [],
                "favoritedBy" => [],
                "propNames" => ["名称", "ジャンル", "上映時間", "上映時期", "レビュー"],
                "records" => [
                    [ "createdBy" => $user_masamune, "君の名は。", "ファンタジー 青春 アニメ", "110分", "2016年", "衝撃的だった。", 
                        "favoritedBy" => [$user_masamune]
                    ]
                ]
            ],
            "書籍" => [
                "isPublic" => true,
                "createdBy" => $user_sumiko,
                "userGroups" => [],
                "favoritedBy" => [],
                "propNames" => ["名称", "ジャンル", "出版社", "著者"],
                "records" => [
                    [ "createdBy" => $user_sumiko, "○○で学ぶ○○", "技術書", "○○社", "○○", 
                        "favoritedBy" => [$user_sumiko]
                    ]
                ]
            ],
            "アニメ作品" => [
                "isPublic" => true,
                "createdBy" => $user_sumiko,
                "userGroups" => [],
                "favoritedBy" => [],
                "propNames" => ["名称", "ジャンル", "年・期", "時間帯", "キャスト"],
                "records" => [
                    [ "createdBy" => $user_suzuo, "○○", "異世界ファンタジー", "2025年秋", "○○", 
                        "favoritedBy" => [$user_suzuo]
                    ]
                ]
            ],
            "カップ麺" => [
                "isPublic" => true,
                "createdBy" => $user_suzuo,
                "userGroups" => [],
                "favoritedBy" => [],
                "propNames" => ["名称", "種類", "発売元", "値段", "販売場所", "おすすめポイント"],
                "records" => [
                    [ "createdBy" => $user_suzuo, "トムヤムクンラーメン", "トムヤムクン", "日清食品", "200円", "イトーヨーカドー\n各種コンビニ", "日本人好みの味",
                        "favoritedBy" => [$user_suzuo]
                    ]
                ]
            ],
            "コンビニスイーツ" => [
                "isPublic" => true,
                "createdBy" => $user_suzuo,
                "userGroups" => [],
                "favoritedBy" => [],
                "propNames" => ["名称", "種類", "値段", "コンビニ", "期間"],
                "records" => [
                    [ "createdBy" => $user_suzuo, "マリトッツォ", "マリトッツォ", "200円", "イトーヨーカドー\n各種コンビニ", "安くてクオリティが高い",
                        "favoritedBy" => [$user_suzuo]
                    ]
                ]
            ],
            "レシピ" => [
                "isPublic" => true,
                "createdBy" => $user_sumiko,
                "userGroups" => [],
                "favoritedBy" => [],
                "propNames" => ["名称", "カテゴリ", "時間", "材料", "手順"],
                "records" => [
                    [ "createdBy" => $user_masamune, "炙りカルボナーラ", "スパゲティ メイン", "15分", "スパゲティ125g\n塩10g\n卵2個\n粉チーズ40g\nベーコン6本\nミックスチーズ30g", "1. カルボナーラを作る。\n2. ミックスチーズをのせて炙る。", 
                        "favoritedBy" => [$user_masamune]
                    ]
                ]
            ],
        ];


        // # データ入力
        foreach ($storeDataset as $storeName => $storeData) {
            // ## Store の作成
            $store = $storeData["createdBy"]->stores()->create([
                "name" => $storeName,
                "is_public" => $storeData["isPublic"]
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
                    "created_by" => $recordData["createdBy"]->id
                ]);

                // ### Record へ値の追加
                foreach ($storeData["propNames"] as $pi => $propName) {
                    // データがある場合のみ作成
                    if (isset($recordData[$pi])) {
                        $this->addPropToRecord($record, $propName,  $recordData[$pi], $propDefs);
                    }
                }

                // ### お気に入りの追加
                foreach ($recordData["favoritedBy"] ?? [] as $user) {
                    $record->favoritedBy()->attach($user->id);
                }
            }

            // ## お気に入りの追加
            foreach ($storeData["favoritedBy"] ?? [] as $user) {
                $store->favoritedBy()->attach($user->id);
            }

            // ## グループの追加
            $store->userGroups()->sync(
                array_map(fn ($userGroup) => $userGroup->id, $storeData["userGroups"])
            );
        }
    }

    private function addPropToRecord(Record $record, string $propName, string $value, $propDefs) {
        try {
            $record->props()->attach($propDefs[$propName]->id, [ "value" => $value ]);
        } catch (Exception $e) {
            Log::info("[StoreSeeder addPropToRecord] cannot add $propName = $value to Record", $record->toArray());
            // throw new Exception("Prop $propName を Record $record->id に追加できませんでした。$propName が重複している可能性があります", 0, $e);
            throw $e;
        }
    }
}
