<?php

declare(strict_types=1);

namespace Ghostwriter\Version\Tests\Unit;

use Ghostwriter\Version\Contract\GrammarInterface;
use Ghostwriter\Version\Contract\LexerInterface;
use Ghostwriter\Version\Contract\TokenInterface;
use Ghostwriter\Version\Enum\TokenKind;
use Ghostwriter\Version\Grammar;
use Ghostwriter\Version\Lexer;
use Ghostwriter\Version\Token;
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
    public function token(int $kind, ?string $value = null): TokenInterface
    {
        return new Token($kind, $value);
    }

    /** @return array<TokenInterface> */
    public function lex(string $text, ?LexerInterface $lexer = null): array
    {
        $lexer ??= $this->createLexer();

        return iterator_to_array($lexer->lex($text));
    }

    /**
     * @covers \Ghostwriter\Version\Grammar::__construct
     * @covers \Ghostwriter\Version\Grammar::createToken
     * @covers \Ghostwriter\Version\Lexer::__construct
     * @covers \Ghostwriter\Version\Lexer::lex
     * @covers \Ghostwriter\Version\Token::__construct
     * @covers \Ghostwriter\Version\Token::getKind
     * @covers \Ghostwriter\Version\Token::getValue
     *
     * @dataProvider versionProvider
     */
    public function testParsesVersionNumbers(
        string $versionString,
        int $expectedMajor,
        int $expectedMinor,
        int $expectedPatch,
        string $expectedPreReleaseValue = '',
        int $expectedReleaseCount = 0,
        string $metaData = ''
    ): void {
        self::assertSameTokens(
            [$this->token(TokenKind::EndOfFile)],
            $this->lex($versionString)
        );

    }

    public function versionProvider(): iterable
    {
        $versions = [
            ['0.0.1', 0, 0, 1],
            ['0.1.2', 0, 1, 2],
            ['1.0.0-alpha', 1, 0, 0, 'alpha'],
            ['3.4.12-dev3', 3, 4, 12, 'dev', 3],
            ['1.2.3-beta.2', 1, 2, 3, 'beta', 2],
            ['v1.2.3-rc', 1, 2, 3, 'rc'],
            ['v1.2.3-rc1', 1, 2, 3, 'rc', 1],
            ['0.0.1-dev+ABC', 0, 0, 1, 'dev', 0, 'ABC'],
            ['0.0.1+git-sha', 0, 0, 1, '', 0, 'git-sha'],
        ];
        foreach ($versions as $version) {
            yield $version[0] => $version;
        }
    }

    private static function assertSameTokens(array $expected = [], $actual = []): void
    {
        self::assertSameSize($expected, $actual);
        foreach ($expected as $index => $expectedToken) {
            if (is_array($expectedToken)) {
                self::assertSameTokens($expectedToken, $actual[$index] ?? []);
            }
            self::assertInstanceOf(TokenInterface::class, $expectedToken);
            self::assertInstanceOf(Token::class, $expectedToken);

            $actualToken = $actual[$index] ?? null;
            self::assertInstanceOf(TokenInterface::class, $actualToken);
            self::assertInstanceOf(Token::class, $actualToken);

            self::assertSame($expectedToken->getKind(), $actualToken->getKind());
            self::assertSame($expectedToken->getValue(), $actualToken->getValue());
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
