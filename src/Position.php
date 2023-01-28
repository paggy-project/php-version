<?php

declare(strict_types=1);

namespace Ghostwriter\Version;

final class Position
{
    private array $table = [
        //
    ];

    public function __construct(
        private int $index,
        private int $line,
        private int $column,
    ) {
    }
}
