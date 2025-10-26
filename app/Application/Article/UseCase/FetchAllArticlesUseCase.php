<?php

namespace App\Application\Article\UseCase;

use App\Application\Article\DTO\ArticleOutput;
use App\Domain\Article\Entity\Article;
use App\Domain\Article\Repository\ArticleRepositoryInterface;

final readonly class FetchAllArticlesUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {
    }

    /**
     * @return ArticleOutput[]
     */
    public function execute(): array
    {
        $articles = $this->articleRepository->fetchAll();

        return array_map(
            fn(Article $article) => $this->toOutput($article),
            $articles
        );
    }

    private function toOutput(Article $article): ArticleOutput
    {
        return new ArticleOutput(
            id: $article->id(),
            url: $article->url()->value(),
            title: $article->title()->value(),
            source: $article->source()->value(),
            publishedAt: $article->publishedAt()?->format('Y-m-d\TH:i:s\Z'),
            createdAt: $article->createdAt()?->format('Y-m-d\TH:i:s\Z'),
            updatedAt: $article->updatedAt()?->format('Y-m-d\TH:i:s\Z')
        );
    }
}
