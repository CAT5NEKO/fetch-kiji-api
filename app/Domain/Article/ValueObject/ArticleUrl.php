<?php

namespace App\Domain\Article\ValueObject;

use InvalidArgumentException;

final readonly class ArticleUrl
{
    private function __construct(
        private string $value
    ) {
    }

    public static function from(string $url): self
    {
        if (empty($url)) {
            throw new InvalidArgumentException('URLは必須です。');
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('URL形式が不正です。');
        }

        if (mb_strlen($url) > 512) {
            throw new InvalidArgumentException('URLは512文字以内で入力してください。');
        }

        return new self($url);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
