<?php

namespace App\Http\Controllers\Public;

use App\Application\Article\UseCase\FetchLatestArticlesUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class FetchTitlesController extends Controller
{
    public function __construct(
        private readonly FetchLatestArticlesUseCase $fetchLatestArticlesUseCase
    ) {}

    public function index(): JsonResponse
    {
        try {
            $articles = $this->fetchLatestArticlesUseCase->execute(100);

            return response()->json([
                'status' => 'success',
                'data' => array_map(function ($article) {
                    return [
                        'id' => $article->id,
                        'url' => $article->url,
                        'title' => $article->title,
                        'published_at' => $article->publishedAt,
                    ];
                }, $articles),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '記事の取得に失敗しました。',
            ], 500);
        }
    }
}
