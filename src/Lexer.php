<?php

declare(strict_types=1);

namespace Ghostwriter\Version;

use Ghostwriter\Version\Contract\GrammarInterface;
use Ghostwriter\Version\Contract\LexerInterface;
use Ghostwriter\Version\Enum\TokenKind;
use RuntimeException;
use Traversable;

final class Lexer implements LexerInterface
{
    /**
     * @var array<int,int>
     */
    private array $characterCodes = [];

    private string $currentCharacter = '';

    private int $currentCharacterCode = 0x0;

    private int $cursor = -1;

    private int $endOfFile = 0;

    private string $text = '';

    public function __construct(
        private readonly GrammarInterface $grammar
    ) {
    }

    public function advance(): void
    {
        ++$this->cursor;
        $this->sync();
    }

    public function back(): void
    {
        --$this->cursor;
        $this->sync();
    }

    public function lex(string $text): Traversable
    {
        $this->cursor = -1;
        $this->text = $text;
        $this->endOfFile = mb_strlen($text) ?: 0;

        $this->advance();

        $character = &$this->currentCharacter;
        $characterCode = &$this->currentCharacterCode;
        while ($this->cursor < $this->endOfFile) {
            yield match (true) {
                CharacterCodes::isDigitChar($characterCode) => $this->lexNumber()->current(),
                CharacterCodes::isAlphaChar($characterCode) => $this->lexAlpha()->current(),
                CharacterCodes::_DOT === $characterCode => [TokenKind::DotToken, $character],
                CharacterCodes::_GREATER_THAN === $characterCode => [TokenKind::GreaterThanToken, $character],
                CharacterCodes::_MINUS === $characterCode => [TokenKind::MinusToken, $character],
                CharacterCodes::_PLUS === $characterCode => [TokenKind::PlusToken, $character],
                default => throw new class(sprintf(
                    'Unknown/Illegal character "%s"; character code: %s.',
                    $character,
                    $characterCode
                )) extends RuntimeException implements VersionExceptionInterface {
                }
            };
            $this->advance();
        }
        yield [TokenKind::EndOfFileToken];
    }

    public function lexAlpha(): Traversable
    {
        $lexeme = '';
        while ($this->cursor < $this->endOfFile) {
            $characterCode = $this->characterCodes[$this->cursor] ??= mb_ord($this->currentCharacter);
            if (! CharacterCodes::isAlphaChar($characterCode)) {
                $this->back();
                break;
            }

            $lexeme .= $this->currentCharacter;

            $this->advance();
        }

        yield [TokenKind::IdentifierToken, $lexeme];
    }

    public function lexNumber(): Traversable
    {
        $lexeme = '';
        while ($this->cursor < $this->endOfFile) {
            $characterCode = $this->characterCodes[$this->cursor] ??= mb_ord($this->currentCharacter);
            if (! CharacterCodes::isDigitChar($characterCode)) {
                $this->back();
                break;
            }

            $lexeme .= $this->currentCharacter;

            $this->advance();
        }

        yield [TokenKind::NumberToken, $lexeme];
    }

    public function sync(): void
    {
        if ($this->cursor >= $this->endOfFile) {
            $this->cursor = $this->endOfFile;
            return;
        }

        $this->currentCharacter = $this->text[$this->cursor];
        $this->currentCharacterCode = $this->characterCodes[$this->cursor] ??= mb_ord($this->currentCharacter);

        match ($this->currentCharacterCode) {
            CharacterCodes::_TAB,
            CharacterCodes::_VERTICAL_TAB,
            CharacterCodes::_NEWLINE,
            CharacterCodes::_SPACE => $this->advance(),
            default => null,
        };
    }
}
