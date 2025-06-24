# 作成時の記録

## モデル図

``` mermaid

class User {
    id: number;
}

class Post {
    id: number;
    contents: string;
}

User ||--o< Post : "create"
User >o--o< Post : "favorite"

```

## 手順

1. Gitリポジトリを用意する
    1. `git init`
    2. `git add .`
    3. `git commit -m 'first-commit'`
    4. `git branch -M main` \
        ブランチ名を main にする
2. docker を起動する
    1. `sudo ./vendor/bin/sail up -d`
3. Breeze を用意する
    1. bash の起動 \
        `sudo ./vendor/bin/sail exec -it laravel.test bash`
    2. Breeze インストールの準備 \
        **sail bash >** `composer require laravel/breeze --dev` \
        ※ 時間かかる (2-12min)
    3. Breeze インストールの実行 \
        `sudo ./vendor/bin/sail artisan breeze:install` (2min)
        - Blade with Alpine -> No -> PHPUnit \
            ※ npm のエラーが出る
    4. マイグレーションの実行 \
        `sudo ./vendor/bin/sail artisan migrate`
    5. 必要な npm パッケージ (Vite, Tailwind CSS 等) のインストール \
        **sail bash >** `npm install` (2mim)
    6. npm vite の実行 \
        **sail bash >** `npm run build`
4. daisyUI と typography をインストールする
    1. **sail bash >** `npm install --save-dev daisyui@4.6.1 @tailwindcss/typography@0.5.10`
    2. [tailwind.config.js](./tailwind.config.js)の編集
        - ``` JavaScript \
            // ...
            import typography from '@tailwindcss/typography';  // 追記
            import daisyui from 'daisyui';                     // 追記
            // ...
            ```
        - ``` JavaScript
            export default {
                // ...
                plugins: [forms, typography, daisyui],     // 編集
            }
            ```
    3. **sail bash >** `npm run build`
5. >モデルを用意する
    1. User モデルを用意する
        1. ~~users テーブルを作成する~~
        2. User モデルを修正する
            1. `sudo ./vendor/bin/sail artisan make:model User`
            2. [app/Models/User.php](app/Models/User.php)
    2. Store モデルを用意する
        1. stores テーブルを作成する
            1. `sudo ./vendor/bin/sail artisan make:migration create_table_stores --create=stores`
            2. [create_table_stores](database/migrations/2025_06_16_025511_create_table_stores.php)
        2. Store モデルを作成する
            1. `sudo ./vendor/bin/sail artisan make:model Store`
            2. [app/Models/Store.php](app/Models/Store.php)
    3. Record モデルを用意する
        1. records テーブルを作成する
            1. `sudo ./vendor/bin/sail artisan make:migration create_table_records --create=records`
            2. [create_table_records](database/migrations/2025_06_16_025626_create_table_records.php)
        2. Record モデルを作成する
            1. `sudo ./vendor/bin/sail artisan make:model Record`
            2. [app/Models/Record.php](app/Models/Record.php)
    4. PropDefinition モデルを用意する
        1. prop_definitions テーブルを作成する
            1. `sudo ./vendor/bin/sail artisan make:migration create_table_prop_definitions --create=prop_definitions`
            2. [create_table_prop_definitions](database/migrations/2025_06_16_044821_create_table_prop_definitions.php)
        2. PropDefinition モデルを作成する
            1. `sudo ./vendor/bin/sail artisan make:model PropDefinition`
            2. [app/Models/PropDefinition.php](app/Models/PropDefinition.php)
    5. RecordItem 中間テーブルを用意する
        1. record_items テーブルを作成する
            1. `sudo ./vendor/bin/sail artisan make:migration create_table_record_items --create=record_items`
            2. [create_table_record_items](database/migrations/2025_06_16_050712_create_table_record_items.php)
        2. ~~RecordItem モデルを作成する~~
            1. `sudo ./vendor/bin/sail artisan make:model RecordItem`
            2. [app/Models/RecordItem.php](app/Models/RecordItem.php)
    6. StoreFavorite 中間テーブルを用意する
        1. store_favorites テーブルを作成する
            1. `sudo ./vendor/bin/sail artisan make:migration create_table_store_favorites --create=store_favorites`
            2. [create_table_store_favorites](database/migrations/2025_06_16_051038_create_table_store_favorites.php)
        2. ~~StoreFavorite モデルを作成する~~ \
            timestamps等、中間テーブルの属性を取得したいわけじゃない場合、別に要らない。作るとしたら usingで指定するのを忘れずに。
            1. `sudo ./vendor/bin/sail artisan make:model StoreFavorite`
            2. [app/Models/StoreFavorite.php](app/Models/StoreFavorite.php)
    7. RecordFavorite 中間テーブルを用意する
        1. record_favorites テーブルを作成する
            1. `sudo ./vendor/bin/sail artisan make:migration create_table_record_favorites --create=record_favorites`
            2. [create_table_record_favorites](database/migrations/2025_06_16_051625_create_table_record_favorites.php)
        2. ~~RecordFavorite モデルを作成する~~
            1. `sudo ./vendor/bin/sail artisan make:model RecordFavorite`
            2. [app/Models/RecordFavorite.php](app/Models/RecordFavorite.php)
    8. マイグレーションを実行する \
        `sudo ./vendor/bin/sail artisan migrate`
6. >Router を編集する
    [routes/web.php](routes/web.php)
    - `/` \
        [GET] welcome / dashboard
    - `/dashboard` \
        [GET] dashboard
    - `/stores` \
        [POST] store.store \
        - `/{id}` \
            [GET] store.show \
            [PUT] store.update \
            [DELETE] store.destroy
            - `/edit` \
                [GET] store.edit
            - `/favorite` \
                [POST] store をお気に入りする
            - `/unfavorite` \
                [DELETE] store のお気に入りを外す
    - [POST] /records
        - [PUT,DELETE] /{id}
            - [GET] /edit
            - [PUSH] /favorite
            - [DELETE] /unfavorite
    - [PUSH] /prop_definitions
        - [PUT,DELETE] /{id}

7. Controller を作成する
    1. ~~UserController を用意する~~
    2. StoreController を用意する\
        `sudo ./vendor/bin/sail artisan make:controller StoreController`
        - show
        - store
        - edit
        - update
        - destroy
    3. RecordController を用意する\
        `sudo ./vendor/bin/sail artisan make:controller RecordController`
        - store
        - edit
        - update
        - destroy
    4. PropDefinitionController を用意する\
        `sudo ./vendor/bin/sail artisan make:controller PropDefinitionController`
        - store
        - update
        - destroy
    5. StoreFavoriteController を用意する\
        `sudo ./vendor/bin/sail artisan make:controller StoreFavoriteController`
        - store
        - destroy
    6. RecordFavoriteController を用意する\
        `sudo ./vendor/bin/sail artisan make:controller RecordFavoriteController`
        - store
        - destroy
    7. DashboardController を用意する\
        `sudo ./vendor/bin/sail artisan make:controller DashboardController`
        - index
        
8. >View を作成する
    [routes/web.php](routes/web.php)
    - `/` \
        [GET] welcome / dashboard
    - `/dashboard` \
        [GET] dashboard
    - `/stores` \
        [POST] store.store \
        - `/{id}` \
            [GET] store.show \
            [PUT] store.update \
            [DELETE] store.destroy
            - `/edit` \
                [GET] store.edit
            - `/favorite` \
                [POST] store をお気に入りする
            - `/unfavorite` \
                [DELETE] store のお気に入りを外す
    - [POST] /records
        - [PUT,DELETE] /{id}
            - [GET] /edit
            - [PUSH] /favorite
            - [DELETE] /unfavorite
    - [PUSH] /prop_definitions
        - [PUT,DELETE] /{id}

9. 修正点洗い出し
    - 必須
    - [x] [全ページ] タイトルを修正
    - [x] [Dashboard お気に入りStore] 作成者の表示
    - [x] [Dashboard お気に入りStore] お気に入りボタンをホバーで表示
    - [x] [Dashboard Store] 公開/非公開の表示
    - [x] [Dashboard 他のユーザーのStore] 右パディングを小さく
    - [x] [Dashboard] 見出し薄くてもよいのでは
    - [x] [Navigation] 「お気に入り」「作成済み」カラー薄く
    - [x] [Navigation] 新規作成ができるように
    - [x] [Store.show] 罫線を横いっぱいに（最後の要素を伸ばす？）
    - [x] [Store.edit] PropDefinition の横幅を小さく
    - [x] [Store.edit] PropDefinition 新規のボタン横幅を大きく
    - [x] [Store.edit Controller] PropDefinition 削除機能
    - [x] [Store.edit] PropDefinition 下部に削除ボタン
    - [x] [Record.show] Storeボタンのクリック部分修正・余白とる
    
    - 任意
    - [x] [Store.show] お気に入りかどうか右上から確認できるように
    - [ ] [新 APIの作成] API経由でデータを入れられるように
    - [ ] [Store] APIを使ってデータを入れる
    - [ ] [Record] 左にバーを付けて見た目を変える
    - [ ] [Store.show] 表示形式をテーブル以外にできるようにする（投稿風等）
    - [ ] [Store.show] フィルタリング・検索機能
    - [ ] [Store.show] 色を付ける
    - [ ] [Record.show] 編集者や最終更新日の情報を表示する

