<?php

namespace App\Application\Article\UseCase;

use App\Application\Article\DTO\ArticleOutput;
use App\Application\Article\DTO\SaveArticleInput;
use App\Domain\Article\Entity\Article;
use App\Domain\Article\Repository\ArticleRepositoryInterface;
use App\Domain\Article\ValueObject\ArticleSource;
use App\Domain\Article\ValueObject\ArticleTitle;
use App\Domain\Article\ValueObject\ArticleUrl;
use DateTimeImmutable;
use InvalidArgumentException;

final readonly class SaveArticleUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {}

    public function execute(SaveArticleInput $input): ArticleOutput
    {
        $url = ArticleUrl::from($input->url);
        $title = ArticleTitle::from($input->title);
        $source = ArticleSource::from($input->source);

        if ($this->articleRepository->existsByUrl($url)) {
            throw new InvalidArgumentException('このURLは既に登録されています。');
        }

        $publishedAt = $input->publishedAt
            ? new DateTimeImmutable($input->publishedAt)
            : null;

        $article = Article::create(
            url: $url,
            title: $title,
            source: $source,
            publishedAt: $publishedAt
        );

        $savedArticle = $this->articleRepository->save($article);

        return $this->toOutput($savedArticle);
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
