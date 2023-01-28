<?php

declare(strict_types=1);

namespace Ghostwriter\Version;

use Ghostwriter\Option\Contract\OptionInterface;
use Ghostwriter\Option\Some;

final class SymbolTable
{
    public function __construct(
        private array $symbols = [],
        private readonly ?self $parent = null,
    ) {
    }

    public function get(string $name): mixed
    {
        return $this->symbols[$name] ?? $this->parent?->get($name) ?? null;
    }

    public function remove(string $name): void
    {
        if (array_key_exists($name, $this->symbols)) {
            unset($this->symbols[$name]);
            return;
        }

        $this->parent?->remove($name);
    }

    public function set(string $name, mixed $value): void
    {
        $this->symbols[$name] = $value;
    }
}
