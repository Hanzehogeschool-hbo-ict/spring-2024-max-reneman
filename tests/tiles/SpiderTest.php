<?php

namespace tiles;

use Hive\Game;
use Hive\tiles\Spider;
use PHPUnit\Framework\TestCase;

class SpiderTest extends TestCase
{

    public function testGetName()
    {
        $spider = new Spider();
        $this->assertEquals('S', $spider->getName());
    }
}