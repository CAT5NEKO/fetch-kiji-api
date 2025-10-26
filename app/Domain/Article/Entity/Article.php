<?php

namespace App\Domain\Article\Entity;

use App\Domain\Article\ValueObject\ArticleSource;
use App\Domain\Article\ValueObject\ArticleTitle;
use App\Domain\Article\ValueObject\ArticleUrl;
use DateTimeImmutable;

final class Article
{
    private function __construct(
        private ?int $id,
        private ArticleUrl $url,
        private ArticleTitle $title,
        private ArticleSource $source,
        private ?DateTimeImmutable $publishedAt,
        private ?DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt
    ) {}

    public static function create(
        ArticleUrl $url,
        ArticleTitle $title,
        ArticleSource $source,
        ?DateTimeImmutable $publishedAt = null
    ): self {
        return new self(
            id: null,
            url: $url,
            title: $title,
            source: $source,
            publishedAt: $publishedAt,
            createdAt: null,
            updatedAt: null
        );
    }

    public static function reconstruct(
        int $id,
        ArticleUrl $url,
        ArticleTitle $title,
        ArticleSource $source,
        ?DateTimeImmutable $publishedAt,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        return new self(
            id: $id,
            url: $url,
            title: $title,
            source: $source,
            publishedAt: $publishedAt,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function url(): ArticleUrl
    {
        return $this->url;
    }

    public function title(): ArticleTitle
    {
        return $this->title;
    }

    public function source(): ArticleSource
    {
        return $this->source;
    }

    public function publishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function createdAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url->value(),
            'title' => $this->title->value(),
            'source' => $this->source->value(),
            'published_at' => $this->publishedAt?->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
