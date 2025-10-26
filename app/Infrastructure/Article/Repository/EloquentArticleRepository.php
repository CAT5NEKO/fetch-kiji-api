<?php

namespace App\Infrastructure\Article\Repository;

use App\Domain\Article\Entity\Article;
use App\Domain\Article\Repository\ArticleRepositoryInterface;
use App\Domain\Article\ValueObject\ArticleSource;
use App\Domain\Article\ValueObject\ArticleTitle;
use App\Domain\Article\ValueObject\ArticleUrl;
use App\Models\Article as EloquentArticle;
use DateTimeImmutable;

final class EloquentArticleRepository implements ArticleRepositoryInterface
{
    public function save(Article $article): Article
    {
        $eloquentArticle = EloquentArticle::create([
            'url' => $article->url()->value(),
            'title' => $article->title()->value(),
            'source' => $article->source()->value(),
            'published_at' => $article->publishedAt()?->format('Y-m-d H:i:s'),
        ]);

        return $this->toDomain($eloquentArticle);
    }

    public function findByUrl(ArticleUrl $url): ?Article
    {
        $eloquentArticle = EloquentArticle::where('url', $url->value())->first();

        return $eloquentArticle ? $this->toDomain($eloquentArticle) : null;
    }

    public function findById(int $id): ?Article
    {
        $eloquentArticle = EloquentArticle::find($id);

        return $eloquentArticle ? $this->toDomain($eloquentArticle) : null;
    }

    public function fetchLatest(int $limit): array
    {
        $eloquentArticles = EloquentArticle::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $eloquentArticles->map(fn ($article) => $this->toDomain($article))->all();
    }

    public function fetchAll(): array
    {
        $eloquentArticles = EloquentArticle::orderBy('created_at', 'desc')->get();

        return $eloquentArticles->map(fn ($article) => $this->toDomain($article))->all();
    }

    public function existsByUrl(ArticleUrl $url): bool
    {
        return EloquentArticle::where('url', $url->value())->exists();
    }

    private function toDomain(EloquentArticle $eloquentArticle): Article
    {
        return Article::reconstruct(
            id: $eloquentArticle->id,
            url: ArticleUrl::from($eloquentArticle->url),
            title: ArticleTitle::from($eloquentArticle->title),
            source: ArticleSource::from($eloquentArticle->source),
            publishedAt: $eloquentArticle->published_at
                ? new DateTimeImmutable($eloquentArticle->published_at->format('Y-m-d H:i:s'))
                : null,
            createdAt: new DateTimeImmutable($eloquentArticle->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($eloquentArticle->updated_at->format('Y-m-d H:i:s'))
        );
    }
}
