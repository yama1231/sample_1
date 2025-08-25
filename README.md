# 予約システム

Vue.jsとCodeIgniter（PHP）を使用した予約管理システムです。

## 構成

- **フロントエンド**: Vue.js 3 + Element Plus
- **バックエンド**: CodeIgniter 3 + PHP + MySQL
- **データベース**: MySQL

## 機能

- 予約の新規作成
- 予約一覧表示
- 予約の編集・更新
- 予約の削除
- サービス管理
- レスポンシブデザイン

## セットアップ

### データベース設定

1. MySQLデータベースを作成:
```sql
CREATE DATABASE reservation_system;
```

2. スキーマを実行:
```bash
mysql -u root -p reservation_system < backend/database/schema.sql
```

### バックエンド（CodeIgniter）

1. Webサーバー（Apache/Nginx）に`backend`ディレクトリを配置
2. `backend/application/config/database.php`でデータベース接続情報を設定
3. `.htaccess`がApacheで正しく動作することを確認

### フロントエンド（Vue.js）

1. 依存関係をインストール:
```bash
cd frontend
npm install
```

2. 開発サーバーを起動:
```bash
npm run serve
```

3. プロダクションビルド:
```bash
npm run build
```

## API エンドポイント

- `GET /api/reservations` - 予約一覧取得
- `GET /api/reservations/:id` - 特定の予約取得
- `POST /api/reservations` - 新規予約作成
- `PUT /api/reservations/:id` - 予約更新
- `DELETE /api/reservations/:id` - 予約削除
- `GET /reservations/services` - サービス一覧取得

## データベーススキーマ

### reservations テーブル
- id (主キー)
- customer_name (顧客名)
- customer_email (顧客メール)
- customer_phone (顧客電話番号)
- service_type (サービス種別)
- reservation_date (予約日)
- reservation_time (予約時間)
- duration (所要時間)
- status (ステータス: pending/confirmed/cancelled)
- notes (備考)
- created_at (作成日時)
- updated_at (更新日時)

### services テーブル
- id (主キー)
- name (サービス名)
- description (説明)
- duration (所要時間)
- price (価格)
- is_active (有効フラグ)
- created_at (作成日時)
- updated_at (更新日時)

## 使用技術

### フロントエンド
- Vue.js 3 (Composition API)
- Vue Router 4
- Element Plus (UIコンポーネント)
- Axios (HTTP通信)

### バックエンド
- CodeIgniter 3
- PHP 7.4+
- MySQL 5.7+

## ライセンス

MIT License