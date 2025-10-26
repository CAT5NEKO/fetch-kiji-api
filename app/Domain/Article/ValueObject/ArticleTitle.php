<?php

namespace App\Domain\Article\ValueObject;

use InvalidArgumentException;

final readonly class ArticleTitle
{
    private function __construct(
        private string $value
    ) {}

    public static function from(string $title): self
    {
        if (empty($title)) {
            throw new InvalidArgumentException('タイトルは必須です。');
        }

        if (mb_strlen($title) > 255) {
            throw new InvalidArgumentException('タイトルは255文字以内で入力してください。');
        }

        return new self($title);
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
