<?php

namespace App\Application\Article\DTO;

final readonly class ArticleOutput
{
    public function __construct(
        public int $id,
        public string $url,
        public string $title,
        public ?string $source,
        public ?string $publishedAt,
        public ?string $createdAt,
        public ?string $updatedAt
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'title' => $this->title,
            'source' => $this->source,
            'published_at' => $this->publishedAt,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
