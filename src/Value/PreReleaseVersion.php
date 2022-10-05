<?php

declare(strict_types=1);

namespace Ghostwriter\SemanticVersion\Value;

final class PreReleaseVersion
{
    public function __construct(
        private string $value
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
