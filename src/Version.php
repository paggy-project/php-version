<?php

declare(strict_types=1);

namespace Ghostwriter\SemanticVersion;

use Ghostwriter\SemanticVersion\Value\BuildVersion;
use Ghostwriter\SemanticVersion\Value\MajorVersion;
use Ghostwriter\SemanticVersion\Value\MinorVersion;
use Ghostwriter\SemanticVersion\Value\PatchVersion;
use Ghostwriter\SemanticVersion\Value\PreReleaseVersion;

final class Version
{
    private MajorVersion $major;

    private MinorVersion $minor;

    private PatchVersion $patch;

    private PreReleaseVersion $prerelease;

    private BuildVersion $build;

    public function __construct(
        private string $value
    ) {
        $this->major = new MajorVersion();
        $this->minor = new MinorVersion();
        $this->patch = new PatchVersion();
        $this->prerelease = new PreReleaseVersion();
        $this->build = new BuildVersion();
    }


    public function getMajor(): MajorVersion
    {
        return $this->major;
    }


    public function getMinor(): MinorVersion
    {
        return $this->minor;
    }


    public function getPatch(): PatchVersion
    {
        return $this->patch;
    }


    public function getPrerelease(): PreReleaseVersion
    {
        return $this->prerelease;
    }


    public function getBuild(): BuildVersion
    {
        return $this->build;
    }

    public static function parse(string $version): self
    {
        return new self($version);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
