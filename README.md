# FetchKiji API

RSS記事収集ましーんのバックエンド



### 大まかな構成

```
app/
├── Domain/              
│   └── Article/
│       ├── Entity/
│       ├── ValueObject/
│       └── Repository/
├── Application/     
│   └── Article/
│       ├── UseCase/ 
│       └── DTO/
├── Infrastructure/
│   └── Article/
│       └── Repository/
└── Http/
    └── Controllers/
```

## エンドポイント


### パンピ購読用(認証なしでOK)
- `GET /api/fetch/titles` - 最新100件の記事を取得

### 管理者API(認証必須)
- `POST /api/admin/save-new-title` - 新規RSSのURLを登録
- `GET /api/admin/fetch-articles` - 登録済み記事一覧を取得



### 起動手順

環境変数をいい感じに弄ってスクリプトを使って起動してください。
