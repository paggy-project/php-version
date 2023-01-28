<?php

declare(strict_types=1);

namespace Ghostwriter\Version\Enum;

enum TokenKind: int
{
    public const AsteriskToken = 0x2A; // *

    public const BarToken = 0x7C; // |

    public const CaretToken = 0x5E; // ^

    public const CloseParenthesesToken = 0x29; // )

    public const DotToken = 0x2E; // .

    public const EndOfFileToken = 0x0;

    public const EqualsToken = 0x3D; // =

    public const ExclamationToken = 0x21; // !

    public const GreaterThanOrEqualsToken = NAN;

    public const GreaterThanToken = 0x3E; // >

    public const IdentifierToken = 0x4;

    public const LessThanOrEqualsToken = NAN;

    public const LessThanToken = 0x3C; // <

    public const MinusToken = 0x2D; // -

    public const NumberToken = 0x2;

    public const OpenParenthesesToken = 0x28; // (

    public const PlusToken = 0x2B; // +

    public const TildeToken = 0x7E; // ~

    public const UnknownToken = 0x1;
}
