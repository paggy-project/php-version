<?php

declare(strict_types=1);

namespace Ghostwriter\SemanticVersion\Tests\Unit;

use Ghostwriter\SemanticVersion\Version;

/**
 * @coversDefaultClass \Ghostwriter\SemanticVersion\Version
 *
 * @internal
 *
 * @small
 */
final class VersionTest extends AbstractTestCase
{
    /**
     * @covers \Ghostwriter\SemanticVersion\Version::__construct
     * @covers \Ghostwriter\SemanticVersion\Version::getValue
     */
    public function testConstruct(): void
    {
        $version = new Version('');

        self::assertEmpty($version->getValue());
    }

    /**
     * @covers \Ghostwriter\SemanticVersion\Version::__construct
     * @covers \Ghostwriter\SemanticVersion\Version::getMinor
     */
    public function testGetMinor(): void
    {
        $version = new Version('');
        self::assertSame(0, $version->getMinor()->getValue());
    }

    /**
     * @covers \Ghostwriter\SemanticVersion\Version::__construct
     * @covers \Ghostwriter\SemanticVersion\Version::getValue
     * @covers \Ghostwriter\SemanticVersion\Version::parse
     */
    public function testParse(): void
    {
        $version = Version::parse('');
        self::assertEmpty($version->getValue());
    }

    /**
     * @covers \Ghostwriter\SemanticVersion\Version::__construct
     * @covers \Ghostwriter\SemanticVersion\Version::getValue
     */
    public function testGetValue(): void
    {
        $version = Version::parse('');
        self::assertEmpty($version->getValue());
    }

    /**
     * @covers \Ghostwriter\SemanticVersion\Version::__construct
     * @covers \Ghostwriter\SemanticVersion\Version::getPatch
     * @covers \Ghostwriter\SemanticVersion\Version::getValue
     */
    public function testGetPatch(): void
    {
        $version = new Version('');
        self::assertSame(0, $version->getPatch()->getValue());
    }

    /**
     * @covers \Ghostwriter\SemanticVersion\Version::__construct
     * @covers \Ghostwriter\SemanticVersion\Version::getMajor
     * @covers \Ghostwriter\SemanticVersion\Version::getValue
     */
    public function testGetMajor(): void
    {
        $version = new Version('');
        self::assertSame(0, $version->getMajor()->getValue());
    }

    /**
     * @covers \Ghostwriter\SemanticVersion\Version::__construct
     * @covers \Ghostwriter\SemanticVersion\Version::getBuild
     * @covers \Ghostwriter\SemanticVersion\Version::getValue
     */
    public function testGetBuild(): void
    {
        $version = new Version('');
        self::assertEmpty($version->getBuild()->getValue());
    }

    /**
     * @covers \Ghostwriter\SemanticVersion\Version::__construct
     * @covers \Ghostwriter\SemanticVersion\Version::getPrerelease
     * @covers \Ghostwriter\SemanticVersion\Version::getValue
     */
    public function testGetPrerelease(): void
    {
        $version = new Version('');
        self::assertEmpty($version->getPrerelease()->getValue());
    }
}
