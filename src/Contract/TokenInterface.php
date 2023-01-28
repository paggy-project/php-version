<?php

declare(strict_types=1);

namespace Ghostwriter\Version\Contract;

interface TokenInterface
{
    public function getKind(): int;

    public function getLexeme(): ?string;
}
