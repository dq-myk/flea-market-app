# フリマアプリ

## 実行環境
- Laravel Framework : 8.83.8
- MySQL Database : 8.0.26
- Nginx Server : 1.21.1
- PHP : 7.4.9-fpm
- MySQL 管理ツール : phpMyadmin
- 開発用メールサーバ : mailhog
- テスト用フレームワーク : PHPUnit

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/

## 環境構築

### 1. Docker ビルド
1. [git clone リンク](https://github.com/coachtech-material/laravel-docker-template)
2. DockerDesktopアプリ起動
3. docker-compose up -d --build を実行

**【 storageへのアクセス設定 】**
1. src/storage/app/public/images フォルダを作成
2. nginx/default.conf へ以下を追記
``` text
    location /storage/ {
        alias /var/www/storage/app/public/;
        try_files $uri $uri/ =404;
    }
```
3. Dockerコンテナの再構築
``` bash
docker-compose down
```
``` bash
docker-compose up -d
```

### 2. Laravel の設定
1. docker-compose exec php bash コマンド実行
2. composer install にてパッケージのインストール
3. 「.env.example」ファイルを複製後 「.env」へ名前を変更
4. データベース接続の為.env へ以下を設定
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. アプリケーションキーの作成
``` bash
php artisan key:generate
```
6. マイグレーション実行
``` bash
php artisan migrate
```
7. ファクトリを使用し、users テーブルにダミーデータを 10 件作成
8. シーダーファイルを使用し、  
   categories テーブルに 14 件、  
   items テーブルに 10 件のダミーデータを作成
9. シーディングの実行
``` bash
php artisan db:seed
```

### 3. HTML・CSS にて各ページの作成
- 商品一覧画面【トップ画面】(/)
- 会員登録画面(/register)
- ログイン画面(/login)
- 商品詳細画面(/item/:item_id)
- 商品購入画面(purchase/:item_id)
- 送付先住所変更画面(purchase/address/:item_id)
- 商品出品画面(/sell)
- プロフィール画面(/mypage)
- プロフィール編集画面【設定画面】(/mypage/profile)

### 4. mailhogでのメール受信テスト
1. mailhogコンテナ作成の為、docker-compose.yml へ以下を追記
``` text
  mailhog:
    image: mailhog/mailhog
    container_name: mailhog
    ports:
      - "1025:1025"  # SMTP port
      - "8025:8025"  # Web UI port
    networks:
      - mailhog_network

networks:
  mailhog_network:
    driver: bridge
```
使用メールアドレス：no-reply@example.com

2. Dockerコンテナの再構築
``` bash
docker-compose down
```
``` bash
docker-compose up -d
```

### 5. PHPUnitテスト
1. テスト用データベース接続の為「.env.example」ファイルを複製後、  
   「.env.testing」へ名前を変更し以下を設定
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_test_db
DB_USERNAME=laravel_test_user
DB_PASSWORD=laravel_test_pass
```
2. phpunit.xmlへ以下を設定
``` text
<server name="DB_CONNECTION" value="mysql"/>
<server name="DB_HOST" value="mysql"/>
<server name="DB_PORT" value="3306"/>
<server name="DB_DATABASE" value="laravel_test_db"/>
<server name="DB_USERNAME" value="laravel_test_user"/>
<server name="DB_PASSWORD" value="laravel_test_pass"/>
```
3. テスト用アプリケーションキーの作成
``` bash
php artisan key:generate --env=testing
```
4. テスト用マイグレーション実行
``` bash
php artisan migrate --env=testing
```
5. 各テストファイル作成(15件)
- 会員登録機能 (RegisterTest.php)
- ログイン機能 (LoginTest.php)
- ログアウト機能 (LogoutTest.php)
- 商品一覧取得 (ItemListTest.php)
- マイリスト一覧取得 (MyListTest.php)
- 商品検索機能 (ItemSearchTest.php)
- 商品詳細情報取得 (ItemDetailTest.php)
- いいね機能 (LikeTest.php)
- コメント送信機能 (CommentTest.php)
- 商品購入機能 (ItemPurchaseTest.php)
- 支払方法選択機能 (PaymentMethodTest.php)
- 配送先変更機能 (AddressChangeTest.php)
- ユーザー情報取得 (ProfileViewTest.php)
- ユーザー情報取得 (ProfileChangeTest.php)
- 出品商品情報登録 (ExhibitRegistrationTest.php)
6. 「php artisan test」にて検証

### 6. ER 図の作成
![ER図](./src/flea-market-test_ER.drawio.svg)
