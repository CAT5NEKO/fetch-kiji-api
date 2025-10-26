<?php

namespace App\Http\Controllers\Admin;

use App\Application\Article\DTO\SaveArticleInput;
use App\Application\Article\UseCase\FetchAllArticlesUseCase;
use App\Application\Article\UseCase\SaveArticleUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveNewTitleRequest;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class SaveNewTitleController extends Controller
{
    public function __construct(
        private readonly SaveArticleUseCase $saveArticleUseCase,
        private readonly FetchAllArticlesUseCase $fetchAllArticlesUseCase
    ) {}

    /**
     * 新規記事を登録する
     */
    public function store(SaveNewTitleRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $input = new SaveArticleInput(
                url: $validated['url'],
                title: $validated['title'],
                source: $validated['source'] ?? null,
                publishedAt: $validated['published_at'] ?? null
            );

            $output = $this->saveArticleUseCase->execute($input);

            return response()->json([
                'status' => 'success',
                'message' => '記事を登録しました。',
                'data' => [
                    'id' => $output->id,
                    'url' => $output->url,
                    'title' => $output->title,
                ],
            ], 201);

        } catch (InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 409);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '記事の登録に失敗しました。',
            ], 500);
        }
    }

    /**
     * 登録済み記事一覧を取得（管理用）
     */
    public function index(): JsonResponse
    {
        try {
            $articles = $this->fetchAllArticlesUseCase->execute();

            return response()->json([
                'status' => 'success',
                'data' => array_map(fn ($article) => $article->toArray(), $articles),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '記事の取得に失敗しました。',
            ], 500);
        }
    }
}
