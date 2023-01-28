<?php

declare(strict_types=1);

namespace Ghostwriter\Version;

final class Version implements VersionInterface
{
    /**
     * @param array<string> $prerelease
     * @param array<string> $build
     */
    public function __construct(
        private readonly int $major = 0,
        private readonly int $minor = 0,
        private readonly int $patch = 0,
        private readonly array $prerelease = [],
        private readonly array $build = []
    ) {
    }

    public function __toString(): string
    {
        return implode('', $this->jsonSerialize());
    }

    public function decrementMajor(): self
    {
        return new self($this->major - 1, $this->minor, $this->patch, $this->prerelease, $this->build);
    }

    public function decrementMinor(): self
    {
        return new self($this->major, $this->minor - 1, $this->patch, $this->prerelease, $this->build);
    }

    public function decrementPatch(): self
    {
        return new self($this->major, $this->minor, $this->patch - 1, $this->prerelease, $this->build);
    }

    public function getBuild(): array
    {
        return $this->build;
    }

    public function getMajor(): int
    {
        return $this->major;
    }

    public function getMinor(): int
    {
        return $this->minor;
    }

    public function getPatch(): int
    {
        return $this->patch;
    }

    public function getPrerelease(): array
    {
        return $this->prerelease;
    }

    public function incrementMajor(): self
    {
        return new self($this->major + 1, 0, 0, $this->prerelease, $this->build);
    }

    public function incrementMinor(): self
    {
        return new self($this->major, $this->minor + 1, 0, $this->prerelease, $this->build);
    }

    public function incrementPatch(): self
    {
        return new self($this->major, $this->minor, $this->patch + 1, $this->prerelease, $this->build);
    }

    /**
     * @return array{major:int,minor:int,patch:int,prerelease:string,build:string}
     */
    public function jsonSerialize(): array
    {
        /** @var null|array{major:int,minor:int,patch:int,prerelease:string,build:string} $data */
        static $data = null;

        return $data ??= [
            'major' => $this->major,
            'minor' => $this->minor,
            'patch' => $this->patch,
            'prerelease' => implode('.', $this->prerelease),
            'build' => implode('.', $this->build),
        ];
    }

    public static function parse(string $version): self
    {
        $grammar = new Grammar();

        $lexer = new Lexer($grammar);

        die(var_dump(iterator_to_array($lexer->lex($version))));

        return new self();
    }
}
