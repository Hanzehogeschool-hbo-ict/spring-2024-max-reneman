<?php

namespace tiles;

use Hive\tiles\Beetle;
use Hive\Game;
use PHPUnit\Framework\TestCase;

class BeetleTest extends TestCase
{
    private Beetle $beetle;
    private Game $game;

    protected function setUp(): void
    {
        $this->beetle = new Beetle();
        $this->game = new Game();
    }

    public function testGetAllValidMoves()
    {
        $from = '0,0';
        $this->game->board[$from] = [['B', 0]];

        $validMoves = $this->beetle->getAllValidMoves($from, $this->game);

        $this->assertContains('0,1', $validMoves);
        $this->assertContains('1,0', $validMoves);
        $this->assertContains('1,-1', $validMoves);
        $this->assertContains('0,-1', $validMoves);
        $this->assertContains('-1,0', $validMoves);
        $this->assertContains('-1,1', $validMoves);
    }

    public function testIsValidMove()
    {
        $from = '0,0';
        $this->game->board[$from] = [['B', 0]];

        // Test valid move
        $to = '0,1';
        $this->assertTrue($this->beetle->isValidMove($from, $to, $this->game));

        // Test invalid move (not a neighbor)
        $to = '2,2';
        $this->assertFalse($this->beetle->isValidMove($from, $to, $this->game));
    }

    public function testGetName()
    {
        $this->assertEquals('B', $this->beetle->getName());
    }
}