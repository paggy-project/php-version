<?php

declare(strict_types=1);

namespace Ghostwriter\Version;

use JsonSerializable;
use Stringable;

interface VersionInterface extends JsonSerializable, Stringable
{
    public function __toString(): string;

    /**
     * Decrements the major number for this Version.
     */
    public function decrementMajor(): self;

    /**
     * Decrements the minor number for this Version.
     */
    public function decrementMinor(): self;

    /**
     * Decrements the patch number for this Version.
     */
    public function decrementPatch(): self;

    /**
     * The build metadata, ignored when determining version precedence.
     *
     * @return array<string> A list of identifiers. Each is a non-empty alphanumeric+hyphen string.
     */
    public function getBuild(): array;

    /** The major version, to be incremented on incompatible changes. */
    public function getMajor(): int;

    /** The minor version, to be incremented when functionality is added in a backwards-compatible manner. */
    public function getMinor(): int;

    /** The patch version, to be incremented when backwards-compatible bug fixes are made. */
    public function getPatch(): int;

    /**
     * The pre-release version identifier, if one exists.
     *
     * @return array<string> A list of non-empty-string alphanumeric+hyphen identifiers.
     *                       If numeric, has no leading zeroes.
     */
    public function getPreRelease(): array;

    /**
     * Increments the major number for this Version.
     */
    public function incrementMajor(): self;

    /**
     * Increments the minor number for this Version.
     */
    public function incrementMinor(): self;

    /**
     * Increments the patch number for this Version.
     */
    public function incrementPatch(): self;

    public function jsonSerialize(): array;
}
