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
<--- 作成したテーブル設計の画像 ----->

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
![ER図](https://github.com/user-attachments/assets/db377156-4887-4194-810a-c63a335efa2a)

## 環境構築
以下のコマンドを実行することで、アプリケーションを構築・起動できます。

```bash
# リポジトリのクローン
git clone https://github.com/yourusername/your-repo.git

# Dockerコンテナのビルドと起動
docker-compose up -d --build

# 環境変数の設定
cp .env.example .env

# Laravel設定
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan storage:link
```

## 他に記載することがあれば記述する
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

