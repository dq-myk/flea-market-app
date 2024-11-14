# フリマアプリ

## 実行環境

- Laravel Framework : 8.83.8
- MySQL Database : 8.0.26
- Nginx Server : 1.21.1
- PHP : 7.4.9-fpm
- MySQL 管理ツール : phpMyadmin

## 環境構築

### 1. Docker ビルド

1. [git clone リンク](https://github.com/coachtech-material/laravel-docker-template)
1. docker-compose up -d --build を実行

### 2. HTML・CSS にて各ページの作成

1. 商品一覧画面【トップ画面】(/)
1. 会員登録画面(/register)
1. ログイン画面(/login)
1. 商品詳細画面(/item/:item_id)
1. 商品購入画面(purchase/:item_id)
1. 送付先住所変更画面(purchase/address/:item_id)
1. 商品出品画面(/sell)
1. プロフィール画面(/mypage)
1. プロフィール編集画面【設定画面】(/mypage/profile)

### 3. Laravel の設定

1. composer install にてパッケージのインストール
1. データベース接続の為.env ファイルを作成
1. マイグレーションにてテーブルを作成
1. ファクトリを使用し、users テーブルにダミーデータを 10 件作成
1. シーダーファイルを使用し、  
   categories テーブルに 14 件、  
   items テーブルに 10 件のダミーデータを作成

### 4. ER 図の作成

![ER図](./src/flea-market-test_ER.drawio.svg)
