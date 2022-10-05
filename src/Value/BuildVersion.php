<?php

declare(strict_types=1);

namespace Ghostwriter\SemanticVersion\Value;

final class BuildVersion
{
    public function __construct(private string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
