<?php

declare(strict_types=1);

namespace Ghostwriter\Version\Contract;

interface GrammarInterface
{
    public function createToken(int $kind, ?string $value): TokenInterface;
}
