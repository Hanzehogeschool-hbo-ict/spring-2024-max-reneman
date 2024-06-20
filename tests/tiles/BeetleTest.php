<?php

namespace tiles;

use Hive\tiles\Beetle;
use Hive\Game;
use PHPUnit\Framework\TestCase;
use stdClass;

class BeetleTest extends TestCase
{
    private Beetle $beetle;
    private Game $game;

    protected function setUp(): void
    {
        $this->beetle = new Beetle();
        $this->game = new Game();
    }

    public function testGetAllValidMovesBase()
    {
        $beetle = new Beetle();
        $game = new stdClass();
        $game->board = [
            '0,0' => [['white', 'B']],
        ];

        $expectedMoves = [
            '0,1', '1,0', '1,-1', '0,-1', '-1,0', '-1,1'
        ];

        $actualMoves = $beetle->getAllValidMoves('0,0', $game);

        sort($expectedMoves);
        sort($actualMoves);

        $this->assertEquals($expectedMoves, $actualMoves);

    }
    public function testGetAllValidMoves()
    {
        $beetle = new Beetle();
        $game = new stdClass();
        $game->board = [
            '0,0' => [['white', 'B']],
            '0,1' => [['black', 'B']],
            '1,0' => [['white', 'Q']],
        ];

        $expectedMoves = [
            '0,1', '1,0', '1,-1', '0,-1', '-1,0', '-1,1'
        ];

        $actualMoves = $beetle->getAllValidMoves('0,0', $game);

        sort($expectedMoves);
        sort($actualMoves);

        $this->assertEquals($expectedMoves, $actualMoves);

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