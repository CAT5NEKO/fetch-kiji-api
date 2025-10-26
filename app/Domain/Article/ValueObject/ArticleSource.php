<?php

namespace App\Domain\Article\ValueObject;

final readonly class ArticleSource
{
    private function __construct(
        private ?string $value
    ) {
    }

    public static function from(?string $source): self
    {
        if ($source !== null && mb_strlen($source) > 255) {
            throw new \InvalidArgumentException('ソース名は255文字以内で入力してください。');
        }

        return new self($source);
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }
}
