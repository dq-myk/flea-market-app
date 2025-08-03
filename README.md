# フリマアプリ（追加機能実装）
```
商品購入～評価までの取引処理機能追加
```

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
5. シーディングの実行
```bash
php artisan db:seed
```

- シーダーファイルへの設定内容  
   取引時に使用するユーザーのテストアカウントをusers テーブルへ 3 件、  
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


**※ログイン時にLaravelログ権限エラーが出たり、ファイル書込みエラーが発生した場合は以下の実行をお願いいたします。**  
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

```
決済時の入力情報は以下の通りです。

メールアドレス：test3@example.com

テスト用カード情報：カード番号「4242 4242 4242 4242」
期限(MM/YY)とセキュリティコード(3桁数字)は任意でお願いします。

カード保有者名：USER3
```

## プロフィール用画像サンプル
```
いちご.jpg、お城.jpg、ポニー.jpg
```

### 4. 追加機能実装
```
・取引チャット（投稿、編集、削除）
・取引チャット入力時の入力情報保持（JavaScript使用）
・評価機能（購入者、出品者、評価の平均値確認）
・取引完了メール確認
```

### 5. ER 図の作成
![ER図](./src/pro-test_ER.drawio.svg)
