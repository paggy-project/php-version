<?php

declare(strict_types=1);

namespace Ghostwriter\Version\Tests\Unit;

use Ghostwriter\Version\Contract\GrammarInterface;
use Ghostwriter\Version\Contract\LexerInterface;
use Ghostwriter\Version\Contract\TokenInterface;
use Ghostwriter\Version\Enum\TokenKind;
use Ghostwriter\Version\Grammar;
use Ghostwriter\Version\Lexer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ghostwriter\Version\Lexer
 *
 * @internal
 *
 * @small
 */
final class LexerTest extends TestCase
{
    /** @return array<TokenInterface> */
    public function lex(string $text, ?LexerInterface $lexer = null): array
    {
        $lexer ??= $this->createLexer();

        return iterator_to_array($lexer->lex($text));
    }

    /**
     * @covers \Ghostwriter\Version\CharacterCodes::isAlphaChar
     * @covers \Ghostwriter\Version\CharacterCodes::isDigitChar
     * @covers \Ghostwriter\Version\Grammar::__construct
     * @covers \Ghostwriter\Version\Grammar::createToken
     * @covers \Ghostwriter\Version\Lexer::__construct
     * @covers \Ghostwriter\Version\Lexer::advance
     * @covers \Ghostwriter\Version\Lexer::back
     * @covers \Ghostwriter\Version\Lexer::lex
     * @covers \Ghostwriter\Version\Lexer::lexAlpha
     * @covers \Ghostwriter\Version\Lexer::lexNumber
     * @covers \Ghostwriter\Version\Lexer::sync
     * @covers \Ghostwriter\Version\Token::__construct
     * @covers \Ghostwriter\Version\Token::getKind
     * @covers \Ghostwriter\Version\Token::getValue
     *
     * @dataProvider versionProvider
     */
    public function testLexVersionNumbers(
        string $versionString,
        int $expectedMajor,
        int $expectedMinor,
        int $expectedPatch,
        string $expectedPreReleaseValue = '',
        int $expectedReleaseCount = 0,
        string $metaData = '',
        array $expectedTokens = []
    ): void {
        self::assertSame($expectedTokens, $this->lex($versionString));
    }

    public function versionProvider(): iterable
    {
        $versions = [
            [
                PHP_EOL . '0.0.1' , 0, 0, 1, '', 0, '', [
                    [TokenKind::NumberToken, '0'],
                    [TokenKind::DotToken, '.'],
                    [TokenKind::NumberToken, '0'],
                    [TokenKind::DotToken, '.'],
                    [TokenKind::NumberToken, '1'],
                    [TokenKind::EndOfFileToken],
                ],
            ],
            [
                '0.0.1'. PHP_EOL, 0, 0, 1, '', 0, '', [
                    [TokenKind::NumberToken, '0'],
                    [TokenKind::DotToken, '.'],
                    [TokenKind::NumberToken, '0'],
                    [TokenKind::DotToken, '.'],
                    [TokenKind::NumberToken, '1'],
                    [TokenKind::EndOfFileToken],
                ],
            ],
            ['0.1.2', 0, 1, 2, '', 0, '', [
                [TokenKind::NumberToken, '0'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '1'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '2'],
                [TokenKind::EndOfFileToken],
            ]],
            ['1.0.0-alpha', 1, 0, 0, 'alpha', 0, '', [
                [TokenKind::NumberToken, '1'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '0'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '0'],
                [TokenKind::MinusToken, '-'],
                [TokenKind::IdentifierToken, 'alpha'],
                [TokenKind::EndOfFileToken],
            ]],
            ['3.4.12-dev3', 3, 4, 12, 'dev', 3, '', [
                [TokenKind::NumberToken, '3'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '4'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '12'],
                [TokenKind::MinusToken, '-'],
                [TokenKind::IdentifierToken, 'dev'],
                [TokenKind::NumberToken, '3'],
                [TokenKind::EndOfFileToken],
            ]],
            ['1.2.3-beta.2', 1, 2, 3, 'beta', 2, '', [
                [TokenKind::NumberToken, '1'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '2'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '3'],
                [TokenKind::MinusToken, '-'],
                [TokenKind::IdentifierToken, 'beta'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '2'],
                [TokenKind::EndOfFileToken],
            ]],
            ['v1.2.3-rc', 1, 2, 3, 'rc', 0, '', [
                [TokenKind::IdentifierToken, 'v'],
                [TokenKind::NumberToken, '1'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '2'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '3'],
                [TokenKind::MinusToken, '-'],
                [TokenKind::IdentifierToken, 'rc'],
                [TokenKind::EndOfFileToken],
            ]],
            ['v1.2.3-rc1', 1, 2, 3, 'rc', 1, '', [
                [TokenKind::IdentifierToken, 'v'],
                [TokenKind::NumberToken, '1'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '2'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '3'],
                [TokenKind::MinusToken, '-'],
                [TokenKind::IdentifierToken, 'rc'],
                [TokenKind::NumberToken, '1'],
                [TokenKind::EndOfFileToken],
            ]],
            ['0.0.1-dev+ABC', 0, 0, 1, 'dev', 0, 'ABC', [
                [TokenKind::NumberToken, '0'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '0'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '1'],
                [TokenKind::MinusToken, '-'],
                [TokenKind::IdentifierToken, 'dev'],
                [TokenKind::PlusToken, '+'],
                [TokenKind::IdentifierToken, 'ABC'],
                [TokenKind::EndOfFileToken],
            ]],
            ['3.2.1+git-sha', 3, 2, 1, '', 0, 'git-sha', [
                [TokenKind::NumberToken, '3'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '2'],
                [TokenKind::DotToken, '.'],
                [TokenKind::NumberToken, '1'],
                [TokenKind::PlusToken, '+'],
                [TokenKind::IdentifierToken, 'git'],
                [TokenKind::MinusToken, '-'],
                [TokenKind::IdentifierToken, 'sha'],
                [TokenKind::EndOfFileToken],
            ]],
        ];
        foreach ($versions as $version) {
            yield $version[0] => $version;
        }
    }

    private function createGrammar(array $rules = [], ?GrammarInterface $grammar = null): GrammarInterface
    {
        return $grammar ?? new Grammar($rules);
    }

    private function createLexer(
        array $rules = [],
        ?GrammarInterface $grammar = null,
        ?LexerInterface $lexer = null
    ): LexerInterface {
        $grammar ??= $this->createGrammar($rules);

        return $lexer ?? new Lexer($grammar);
    }
}
