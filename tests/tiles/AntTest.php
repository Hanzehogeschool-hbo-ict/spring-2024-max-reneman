<?php

namespace tiles;

use Hive\tiles\Ant;
use PHPUnit\Framework\TestCase;

class AntTest extends TestCase
{
    protected Ant $ant;
    protected \stdClass $game;

    protected function setUp(): void
    {
        $this->ant = new Ant();
        $this->game = new \stdClass();
        $this->game->board = [];
    }

    public function testGetName()
    {
        $this->assertEquals('A', $this->ant->getName());
    }

    public function testIsValidMove()
    {
        // Rule c
        $this->game->board['0,0'] = 'A';
        $this->assertFalse($this->ant->isValidMove('0,0', '0,0', $this->game));

        // Rule d
        $this->game->board['0,1'] = 'A';
        $this->assertFalse($this->ant->isValidMove('0,0', '0,1', $this->game));

        // Rule a and b
    }

//    public function testGetAllValidMoves()
//    {
//
//    }
}