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

use OpxCore\PathSet\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    protected function makePath(): Path
    {
        return new Path('first', ['second', 'third']);
    }

    public function testGetPath(): void
    {
        $ns = $this->makePath();
        $this->assertEquals(
            'first',
            $ns->primary()
        );
    }

    public function testSetPath(): void
    {
        $ns = $this->makePath();
        $ns->setPrimary('path');
        $this->assertEquals(
            'path',
            $ns->primary()
        );
    }

    public function testGetAlternates(): void
    {
        $ns = $this->makePath();
        $this->assertEquals(
            ['second', 'third'],
            $ns->alternates()
        );
    }

    public function testClearAlternates(): void
    {
        $ns = $this->makePath();
        $ns->clearAlternates();
        $this->assertEquals(
            [],
            $ns->alternates()
        );
    }

    public function testSetAlternates(): void
    {
        $ns = $this->makePath();
        $ns->setAlternates(['alt1', 'alt2']);
        $this->assertEquals(
            ['alt1', 'alt2'],
            $ns->alternates()
        );
    }

    public function testAddAlternates(): void
    {
        $ns = $this->makePath();
        $ns->addAlternates(['alt1', 'alt2']);
        $this->assertEquals(
            ['second', 'third', 'alt1', 'alt2'],
            $ns->alternates()
        );
    }

    public function testCompile():void
    {
        $ns = $this->makePath();
        $ns->addAlternates(['alt1', 'first']);
        $this->assertEquals(
            ['first', 'alt1', 'third', 'second'],
            $ns->all()
        );
    }
}
