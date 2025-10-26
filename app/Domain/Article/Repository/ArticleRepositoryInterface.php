<?php

namespace App\Domain\Article\Repository;

use App\Domain\Article\Entity\Article;
use App\Domain\Article\ValueObject\ArticleUrl;

interface ArticleRepositoryInterface
{
    /**
     * 記事を保存する
     */
    public function save(Article $article): Article;

    /**
     * URLで記事を検索する
     */
    public function findByUrl(ArticleUrl $url): ?Article;

    /**
     * IDで記事を検索する
     */
    public function findById(int $id): ?Article;

    /**
     * 最新のN件の記事を取得する
     */
    public function fetchLatest(int $limit): array;

    /**
     * すべての記事を取得する（新しい順）
     */
    public function fetchAll(): array;

    /**
     * URLが既に存在するか確認する
     */
    public function existsByUrl(ArticleUrl $url): bool;
}
