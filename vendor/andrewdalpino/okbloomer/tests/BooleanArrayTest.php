<?php

namespace OkBloomer\Tests;

use OkBloomer\BooleanArray;
use PHPUnit\Framework\TestCase;

/**
 * @group Base
 * @covers \OkBloomer\BooleanArray
 */
class BooleanArrayTest extends TestCase
{
    /**
     * @var \OkBloomer\BooleanArray
     */
    protected $bitmap;

    /**
     * @before
     */
    protected function setUp() : void
    {
        $this->bitmap = new BooleanArray(1024);
    }

    /**
     * @test
     */
    public function offsetSet() : void
    {
        $this->assertFalse($this->bitmap[42]);

        $this->bitmap[42] = true;

        $this->assertTrue($this->bitmap[42]);
    }
}
