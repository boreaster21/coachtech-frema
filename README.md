# メルカリ風アプリケーション

## アプリケーション名
Coachtechフリマ
<img width="1265" alt="image" src="https://github.com/user-attachments/assets/ec7c9a7a-d222-4228-88df-cbf0596e3d04" />

## 作成した目的
C2C (個人間取引) を想定し、商品出品、購入、決済機能を実装し、学習目的として作成しました。

## アプリケーションURL
- デプロイ先のURL: **未設定**

## 他のリポジトリ
特に無し

## 機能一覧
1. ユーザー登録/ログイン機能
2. ログアウト機能
3. プロフィール作成、更新機能
4. アカウント削除機能
5. 商品出品機能
6. 商品一覧表示/検索機能
7. 商品購入機能 (支払い方法: コンビニ払い、Stripe決済など)
8. 購入履歴の表示機能
9. お気に入り登録機能
10. コメント機能
11. ユーザー、コメント削除機能
12. メール送信機能

## 使用技術 (実行環境)
- **OS:** Ubuntu
- **Webサーバー:** nginx 1.27.2
- **PHP:** 8.2
- **フレームワーク:** Laravel 11
- **データベース:** MySQL 8.0
- **認証:** Laravel Breeze 2.2
- **決済:** Stripe API
- **仮想環境:** Docker + Docker Compose
- **バージョン管理:** Git & GitHub

## テーブル設計
<img width="515" alt="image" src="https://github.com/user-attachments/assets/c1084027-4f6c-4e3a-a02a-f0ea35db2756" />


### テーブル概要:
#### usersテーブル
- ユーザー情報 (id, name, email, role_id)

#### productsテーブル
- 商品情報 (id, user_id, name, price, description, is_sold)

#### purchasesテーブル
- 購入履歴 (id, user_id, product_id, payment_id)

#### paymentsテーブル
- 支払い方法 (id, method_name)

#### favoritesテーブル
- ユーザーのお気に入り情報 (id, user_id, product_id)

#### commentsテーブル
- 商品へのコメント (id, user_id, product_id, content)

#### products_categories & products_conditions
- 商品とカテゴリ、状態を紐づける中間テーブル

## ER図
![ER図](https://github.com/user-attachments/assets/9ed53b56-76c1-48bc-9fd8-7976bb7666fd)

## 環境構築
以下のコマンドを実行することで、アプリケーションを構築・起動できます。

```bash
# リポジトリのクローン
git clone git@github.com:boreaster21/coachtech-frema.git

# Dockerコンテナのビルドと起動
docker-compose up -d --build

# Laravel のパッケージのインストール
docker-compose exec php bash
composer install

# .env ファイルの作成と修正
cp .env.example .env
```

```bash
- APP_LOCALE=en
- APP_FALLBACK_LOCALE=en
- APP_FAKER_LOCALE=en_US
+ APP_LOCALE=ja
+ APP_FALLBACK_LOCALE=en
+ APP_FAKER_LOCALE=ja_JP

APP_MAINTENANCE_DRIVER=file
- # APP_MAINTENANCE_STORE=database
~~~
- DB_CONNECTION=sqlite
- # DB_HOST=127.0.0.1
- # DB_PORT=3306
- # DB_DATABASE=laravel
- # DB_USERNAME=root
- # DB_PASSWORD=
+ DB_CONNECTION=mysql
+ DB_HOST=mysql
+ DB_PORT=3306
+ DB_DATABASE=laravel_db
+ DB_USERNAME=laravel_user
+ DB_PASSWORD=laravel_pass
~~~
- MAIL_MAILER=log
- MAIL_HOST=127.0.0.1
MAIL_PORT=2525
- MAIL_USERNAME=null
- MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
+ MAIL_MAILER=smtp
+ MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
+ MAIL_USERNAME='ご自身のmailtrapアカウントから引用してください'
+ MAIL_PASSWORD='ご自身のmailtrapアカウントから引用してください'
~~~
+ STRIPE_KEY=''ご自身のSTRIPEアカウントから引用してください'
+ STRIPE_SECRET='ご自身のSTRIPEアカウントから引用してください'
```


```bash
# Laravel設定
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
※src/storage/app/public/product-iconsにサンプル商品画像をいくつか保存してからシードしてください。
docker-compose exec app php artisan storage:link
```

## 補足
- 管理者用アカウント:
  - メール: `admin@example.com`
  - パスワード: `adminpassword`

- テスト用アカウント:
  - メール: `test@example.com`
  - パスワード: `password`

- 決済テスト用クレジットカード:
  - **カード番号:** `4242 4242 4242 4242`
  - **有効期限:** `12/34`
  - **CVC:** `123`

