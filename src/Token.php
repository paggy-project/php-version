<?php

declare(strict_types=1);

namespace Ghostwriter\Version;

use Ghostwriter\Version\Contract\TokenInterface;

final class Token implements TokenInterface
{
    public function __construct(
        private readonly int $kind,
        private readonly ?string $value = null,
    ) {
    }

    public function getKind(): int
    {
        return $this->kind;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
