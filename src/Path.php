<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\PathSet;

class Path
{
    /** @var string Primary path */
    protected string $primary;

    /** @var array|null Alternate paths */
    protected array $alternates = [];

    /**
     * Path constructor.
     *
     * @param string $primary
     * @param array $alternates
     *
     * @return  void
     */
    public function __construct(string $primary, array $alternates = [])
    {
        $this->primary = $primary;
        $this->alternates = $alternates;
    }

    /**
     * Primary path getter.
     *
     * @return  string
     */
    public function primary(): string
    {
        return $this->primary;
    }

    /**
     * Get alternates.
     *
     * @return  array
     */
    public function alternates(): array
    {
        return $this->alternates;
    }

    /**
     * Get path set. Alternates first in reverse order of appearance without duplicates.
     *
     * @return  array
     */
    public function all(): array
    {
        $combined[] = $this->primary;

        if (!empty($this->alternates)) {
            $combined = array_merge($combined, $this->alternates);
            $combined = array_unique(array_reverse($combined));
        }

        return $combined;
    }

    /**
     * Primary path setter.
     *
     * @param string $primary
     *
     * @return  void
     */
    public function setPrimary(string $primary): void
    {
        $this->primary = $primary;
    }

    /**
     * Sets or merges alternates.
     *
     * @param array $alternates
     * @param bool $merge
     *
     * @return  void
     *
     * @internal
     */
    private function writeAlternates(array $alternates, bool $merge): void
    {
        $this->alternates = $merge
            ? array_merge($this->alternates, $alternates)
            : $alternates;
    }

    /**
     * Set alternates for namespace.
     *
     * @param array $alternates
     *
     * @return  void
     */
    public function setAlternates(array $alternates = []): void
    {
        $this->writeAlternates($alternates, false);
    }

    /**
     * Merges alternates to existing namespace alternates. New alternates has higher priority.
     *
     * @param array $alternates
     *
     * @return  void
     */
    public function addAlternates(array $alternates = []): void
    {
        $this->writeAlternates($alternates, true);
    }

    /**
     * Clears namespace alternates.
     *
     * @return  void
     */
    public function clearAlternates(): void
    {
        $this->writeAlternates([], false);
    }
}
