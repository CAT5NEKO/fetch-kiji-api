<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'FetchKiji API',
        'version' => '1.0.0',
        'endpoints' => [
            'GET /api/fetch/titles' => '最新100件の記事を取得',
            'POST /api/admin/save-new-title' => '新規記事を登録 (要認証)',
            'GET /api/admin/fetch-articles' => '全記事を取得 (要認証)',
        ],
    ]);
});

require __DIR__.'/settings.php';
