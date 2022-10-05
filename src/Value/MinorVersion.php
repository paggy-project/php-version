<?php

declare(strict_types=1);

namespace Ghostwriter\SemanticVersion\Value;

final class MinorVersion
{
    public function __construct(
        private int $value
    ) {
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
