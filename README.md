# フリマアプリ

## 実行環境
- Laravel Framework : 8.83.8
- MySQL Database : 8.0.26
- Nginx Server : 1.21.1
- PHP : 7.4.9-fpm
- MySQL 管理ツール : phpMyadmin


## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/

## 環境構築

### 1. Docker ビルド
1. [git clone リンク](https://github.com/dq-myk/pro-test)
1. DockerDesktop アプリを起動後に以下を実行
```docker
docker-compose up -d --build
```
2. 以下のコマンドでメール認証用MailHogコンテナを起動  
```docker
docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog
```
**メール認証用、mailhogアクセス先 : http://localhost:8025/**  
**※メールが届かない場合は、再送信をお願いします。**


### 2. Laravel の設定
- 以下を実行しPHPコンテナ内にログイン
```docker
docker-compose exec php bash
```
1. Laravelのパッケージをインストール  
```bash
composer install
```
2. 「.env.example」ファイルを複製後 「.env」へ名前を変更  
```bash
cp .env.example .env
```
3. アプリケーションキーの生成  
```bash
php artisan key:generate
```
4. マイグレーション実行
```bash
php artisan migrate
```

- ファクトリへの設定内容  
    users テーブルにダミーデータを 10 件、  
    （ダミー用のパスワードは全てpasswordを設定）  
    items テーブルにダミーデータを 10 件作成

- シーダーファイルへの設定内容  
   取引時に使用するユーザーのテストアカウントをusers テーブルへ個別で 3 件、  
   出品商品をitemsテーブルへ 10 件作成  

### テストアカウント
      name: ユーザー1(商品ID：C001～C005の出品者)  
      email: test1@example.com  
      password: password  
      --------------------------------------------
      name: ユーザー2(商品ID：C006～C010の出品者)  
      email: test2@example.com  
      password: password  
      --------------------------------------------
      name: ユーザー3(購入者)  
      email: test3@example.com  
      password: password  
      --------------------------------------------

5. シーディングの実行
```bash
php artisan db:seed
```

**※ログイン時にLaravelログ権限エラーが出た場合は以下の実行をお願いいたします。**  
- 以下を実行しPHPコンテナ内にログイン
```docker
docker-compose exec php bash
```
```bash
cd ../
```
```bash
chmod -R 777 www/.*
```
```bash
cd www
```

## Stripeについて
コンビニ支払いとカード支払いのオプションがありますが、決済画面にてコンビニ支払いを選択しますと、支払手順を印刷する画面に遷移します。そのため、カード支払いを成功させた場合に意図する画面遷移が行える想定です。<br>

また、StripeのAPIキーは.envファイル内へ以下のように設定してください。
```
STRIPE_PUBLIC_KEY="パブリックキー"
STRIPE_SECRET_KEY="シークレットキー"
```

以下のStripe公式サイトにてアカウント作成をお願いします。<br>
https://docs.stripe.com/payments/checkout?locale=ja-JP


### 3. PHPUnitテスト
- 以下を実行しPHPコンテナ内にログイン
```docker
docker-compose exec php bash
```
1. テスト用マイグレーション実行
``` bash
php artisan migrate --env=testing
```
2. テスト実行
``` bash
php artisan test
```

**※本番環境と同一のデータベースを使用してテスト用テーブルを作成する為、**  
　**テスト用マイグレーションを実行すると、本番環境のデータが全て消えてしまいます。**  
　**テストケース検証後に本番環境の再確認が必要な場合は、お手数ですが再度以下の実行を**  
　**お願いいたします。**
- 以下を実行しPHPコンテナ内にログイン
```docker
docker-compose exec php bash
```
- マイグレーション実行
```bash
php artisan migrate
```
- シーディングの実行
```bash
php artisan db:seed
```

### 4. ER 図の作成
![ER図](./src/pro-test_ER.drawio.svg)
