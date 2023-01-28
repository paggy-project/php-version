<?php

declare(strict_types=1);

namespace Ghostwriter\Version;

use Ghostwriter\Version\Contract\TokenInterface;
use Traversable;

final class Tokens
{
    /**
     * @param array<TokenInterface> $tokens
     */
    public function __construct(
        private array $tokens = []
    ) {
    }

    public function addToken(TokenInterface $token): void
    {
        $this->tokens[] = $token;
    }

    public function addTokens(TokenInterface ...$tokens): void
    {
        $this->tokens = array_merge($this->tokens, $tokens);
    }

    /**
     * @return array<TokenInterface>
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @return Traversable<TokenInterface>
     */
    public function yieldTokens(): Traversable
    {
        yield from $this->tokens;
    }
}
