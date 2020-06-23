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

use ArrayAccess;
use OpxCore\PathSet\Exceptions\PathAlreadyExistsException;
use OpxCore\PathSet\Exceptions\PathInvalidArgumentException;
use OpxCore\PathSet\Exceptions\PathNotExistsException;

class PathSet implements ArrayAccess
{
    /** @var array Paths store */
    protected array $paths = [];

    /**
     * PathSet constructor.
     *
     * @param array|null $paths
     *
     * @return  void
     */
    public function __construct(?array $paths)
    {
        if (!empty($paths)) {
            foreach ($paths as $name => $entries) {
                $path = $entries[0];
                unset($entries[0]);
                $this[$name] = new Path($path, $entries);
            }
        }
    }

    /**
     * Add path.
     *
     * @param string|null $name
     * @param string|mixed $primary
     * @param array $alternates
     *
     * @return  Path
     *
     * @throws  PathNotExistsException
     * @throws  PathInvalidArgumentException
     */
    public function add(?string $name, $primary, array $alternates = []): Path
    {
        $name ??= '*';

        $path = new Path($primary, $alternates);

        $this[$name] = $path;

        return $path;
    }

    /**
     * Get set of paths for name.
     *
     * @param string|null $name
     *
     * @return  array
     *
     * @throws  PathNotExistsException
     */
    public function get(?string $name = null): array
    {
        $name ??= '*';

        $compiled = $this[$name]->all();

        if (isset($this['*'])) {
            $global = $this['*']->all();
            $compiled = array_merge($compiled, array_diff($global, $compiled));
        }

        return $compiled;
    }

    /**
     * Whether a name set.
     *
     * @param string $name
     *
     * @return  bool
     */
    public function offsetExists($name): bool
    {
        return isset($this->paths[$name]);
    }

    /**
     * Get Path assigned to name.
     *
     * @param string $name
     *
     * @return  Path
     *
     * @throws  PathNotExistsException
     */
    public function offsetGet($name): Path
    {
        if (!$this->offsetExists($name)) {
            throw new PathNotExistsException("Name [{$name}] was not registered.");
        }

        return $this->paths[$name];
    }

    /**
     * Set Path to name.
     *
     * @param string $name
     * @param Path $path
     *
     * @return  void
     *
     * @throws  PathNotExistsException
     * @throws  PathInvalidArgumentException
     */
    public function offsetSet($name, $path): void
    {
        if ($this->offsetExists($name)) {
            throw new PathAlreadyExistsException("Name [{$name}] was already registered.");
        }

        if (!$path instanceof Path) {
            $type = gettype($path);
            $type = ($type !== 'object') ?: get_class($path);
            throw new PathInvalidArgumentException("New value must be type of [Path], [{$type}] given.");
        }

        $this->paths[$name] = $path;
    }

    /**
     * Unset name.
     *
     * @param string $name
     *
     * @return  void
     */
    public function offsetUnset($name): void
    {
        unset($this->paths[$name]);
    }
}
