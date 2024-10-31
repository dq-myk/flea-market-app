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

1. お問い合わせフォーム(/)
1. お問い合わせフォーム確認ページ(/confirm)
1. サンクスページ(/thanks)
1. 管理画面(/admin)
1. ユーザー登録ページ(/refister)
1. ログインページ(/login)

### 3. Laravel の設定

1. composer install にてパッケージのインストール
1. データベース接続の為.env ファイルを作成
1. マイグレーションにてデータ作成
1. ファクトリを使用し、contacts テーブルにダミーデータを 35 件作成
1. シーダーファイルを使用し、categories テーブルにダミーデータを 5 件作成

### 4. ER 図の作成

![ER図](./src/flea-market-test_ER.drawio.svg)