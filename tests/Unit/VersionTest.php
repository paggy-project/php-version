<?php

declare(strict_types=1);

namespace Ghostwriter\Version\Tests\Unit;

use Ghostwriter\Version\Version;
use Ghostwriter\Version\VersionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ghostwriter\Version\Version
 *
 * @internal
 *
 * @small
 */
final class VersionTest extends TestCase
{
    public const VERSION_BUILD = ['build', 'sha'];

    public const VERSION_JSON = '{"major":1,"minor":0,"patch":0,"prerelease":["alpha","beta"],"build":["build","sha"]}';

    public const VERSION_PRE_RELEASE = ['alpha', 'beta'];

    public const VERSION_STRING = '1.0.0-alpha.beta+build.sha';

    public const VERSION_VALUE = [1, 0, 0, self::VERSION_PRE_RELEASE, self::VERSION_BUILD];

    /**
     * @covers \Ghostwriter\Version\Version::__construct
     */
    public function testConstruct(): void
    {
        $version = new Version(...self::VERSION_VALUE);
        self::assertInstanceOf(VersionInterface::class, $version);
    }

    /**
     * @covers \Ghostwriter\Version\Version::__construct
     * @covers \Ghostwriter\Version\Version::getBuild
     */
    public function testGetBuild(): void
    {
        $version = new Version(...self::VERSION_VALUE);
        self::assertSame(self::VERSION_BUILD, $version->getBuild());
    }

    /**
     * @covers \Ghostwriter\Version\Version::__construct
     * @covers \Ghostwriter\Version\Version::getMajor
     */
    public function testGetMajor(): void
    {
        $version = new Version(...self::VERSION_VALUE);
        self::assertSame(1, $version->getMajor());
    }

    /**
     * @covers \Ghostwriter\Version\Version::__construct
     * @covers \Ghostwriter\Version\Version::getMinor
     */
    public function testGetMinor(): void
    {
        $version = new Version(...self::VERSION_VALUE);
        self::assertSame(0, $version->getMinor());
    }

    /**
     * @covers \Ghostwriter\Version\Version::__construct
     * @covers \Ghostwriter\Version\Version::getPatch
     */
    public function testGetPatch(): void
    {
        $version = new Version(...self::VERSION_VALUE);
        self::assertSame(0, $version->getPatch());
    }

    /**
     * @covers \Ghostwriter\Version\Version::__construct
     * @covers \Ghostwriter\Version\Version::getBuild
     */
    public function testGetPrerelease(): void
    {
        $version = new Version(...self::VERSION_VALUE);
        self::assertSame(self::VERSION_PRE_RELEASE, $version->getPreRelease());
    }

    /**
     * @covers \Ghostwriter\Version\Version::__construct
     * @covers \Ghostwriter\Version\Version::__toString
     * @covers \Ghostwriter\Version\Version::getMajor
     * @covers \Ghostwriter\Version\Version::incrementMajor
     * @covers \Ghostwriter\Version\Version::jsonSerialize
     */
    public function testIncrementMajor(): void
    {
        $version = new Version(...self::VERSION_VALUE);
        self::assertSame(self::VERSION_STRING, (string) $version);
        self::assertSame(1, $version->getMajor());

        $versionDecrementMajor = $version->incrementMajor();

        self::assertNotSame($version, $versionDecrementMajor);
        self::assertGreaterThan($version->getMajor(), $versionDecrementMajor->getMajor());
    }

    /**
     * @covers \Ghostwriter\Version\Version::__construct
     * @covers \Ghostwriter\Version\Version::__toString
     * @covers \Ghostwriter\Version\Version::getMinor
     * @covers \Ghostwriter\Version\Version::incrementMinor
     * @covers \Ghostwriter\Version\Version::jsonSerialize
     */
    public function testIncrementMinor(): void
    {
        $version = new Version(...self::VERSION_VALUE);
        self::assertSame(self::VERSION_STRING, (string) $version);
        self::assertSame(0, $version->getMinor());

        $versionDecrementMajor = $version->incrementMinor();

        self::assertNotSame($version, $versionDecrementMajor);
        self::assertGreaterThan($version->getMinor(), $versionDecrementMajor->getMinor());
    }

    /**
     * @covers \Ghostwriter\Version\Version::__construct
     * @covers \Ghostwriter\Version\Version::__toString
     * @covers \Ghostwriter\Version\Version::getPatch
     * @covers \Ghostwriter\Version\Version::incrementPatch
     * @covers \Ghostwriter\Version\Version::jsonSerialize
     */
    public function testIncrementPatch(): void
    {
        $version = new Version(...self::VERSION_VALUE);
        self::assertSame(self::VERSION_STRING, (string) $version);
        self::assertSame(0, $version->getPatch());

        $versionDecrementMajor = $version->incrementPatch();

        self::assertNotSame($version, $versionDecrementMajor);
        self::assertGreaterThan($version->getPatch(), $versionDecrementMajor->getPatch());
    }

    /**
     * @covers \Ghostwriter\Version\Version::__construct
     * @covers \Ghostwriter\Version\Version::__toString
     * @covers \Ghostwriter\Version\Version::jsonSerialize
     */
    public function testJsonSerialize(): void
    {
        $version = new Version(...self::VERSION_VALUE);
        self::assertSame(self::VERSION_STRING, (string) $version);
        self::assertSame(self::VERSION_JSON, json_encode($version));
    }

    /**
     * @covers \Ghostwriter\Version\Version::__construct
     * @covers \Ghostwriter\Version\Version::__toString
     * @covers \Ghostwriter\Version\Version::jsonSerialize
     */
    public function testToString(): void
    {
        $version = new Version(...self::VERSION_VALUE);
        self::assertSame(self::VERSION_STRING, (string) $version);
    }
}
