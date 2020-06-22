<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\Tests\PathSet;

use OpxCore\PathSet\Exceptions\PathAlreadyExistsException;
use OpxCore\PathSet\Exceptions\PathInvalidArgumentException;
use OpxCore\PathSet\Exceptions\PathNotExistsException;
use OpxCore\PathSet\PathSet;
use PHPUnit\Framework\TestCase;

class PathSetTest extends TestCase
{
    protected function makePathSet(): array
    {
        $pathSet = new PathSet(['*' => ['first', 'second', 'third']]);

        $path = $pathSet->add('name', 'one', ['two', 'three']);

        return [$pathSet, $path];
    }

    public function testOffsetGet(): void
    {
        [$pathSet, $path] = $this->makePathSet();

        $this->assertEquals(
            $path,
            $pathSet['name']
        );
    }

    public function testOffsetGetNotExisting(): void
    {
        [$pathSet, $path] = $this->makePathSet();

        $this->expectException(PathNotExistsException::class);

        $test = $pathSet['some'];

        unset($path, $test);
    }

    public function testOffsetSetInvalid(): void
    {
        [$pathSet, $path] = $this->makePathSet();

        $this->expectException(PathInvalidArgumentException::class);

        $pathSet['some'] = ['wrong'];

        unset($path);
    }

    public function testOffsetSetOverwrite(): void
    {
        [$pathSet, $path] = $this->makePathSet();

        $this->expectException(PathAlreadyExistsException::class);

        $pathSet['name'] = $path;

        unset($path);
    }

    public function testOffsetUnset(): void
    {
        [$pathSet, $path] = $this->makePathSet();

        unset($pathSet['name']);

        $this->assertFalse(isset($pathSet['name']));

        unset($path);
    }

    public function testGetName(): void
    {
        [$pathSet, $path] = $this->makePathSet();

        $this->assertEquals(
            [
                'three',
                'two',
                'one',
                'third',
                'second',
                'first',
            ],
            $pathSet->get('name')
        );
        unset($path);
    }

    public function testGetGlobal(): void
    {
        [$pathSet, $path] = $this->makePathSet();

        $this->assertEquals(
            [
                'third',
                'second',
                'first',
            ],
            $pathSet->get()
        );
        unset($path);
    }
}
