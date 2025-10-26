<?php

namespace App\Application\Article\DTO;

final readonly class SaveArticleInput
{
    public function __construct(
        public string $url,
        public string $title,
        public ?string $source = null,
        public ?string $publishedAt = null
    ) {
    }
}
