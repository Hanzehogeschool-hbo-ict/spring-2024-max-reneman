<?php

namespace tiles;

use Hive\tiles\Queen;
use PHPUnit\Framework\TestCase;
use stdClass;

class QueenTest extends TestCase
{

    public function testGetName()
    {
        $queen = new Queen();
        $this->assertEquals('Q', $queen->getName());
    }

    public function testIsValidMove()
    {
        $queen = new Queen();
        $game = new stdClass();
        $game->board = [
            '0,0' => [['white', 'Q']],
            '0,1' => [['white', 'Q']]
        ];

        $this->assertTrue($queen->isValidMove('0,0', '1,0', $game));
        $this->assertTrue($queen->isValidMove('0,0', '-1,1', $game));

        $this->assertFalse($queen->isValidMove('0,0', '0,0', $game));
        $this->assertFalse($queen->isValidMove('0,0', '0,2', $game));
        $this->assertFalse($queen->isValidMove('0,0', '2,0', $game));
        $this->assertFalse($queen->isValidMove('0,0', '2,-2', $game));
        $this->assertFalse($queen->isValidMove('0,0', '0,-2', $game));
        $this->assertFalse($queen->isValidMove('0,0', '-2,0', $game));
        $this->assertFalse($queen->isValidMove('0,0', '-2,2', $game));
    }

    public function testGetAllValidMoves()
    {
        $queen = new Queen();
        $game = new stdClass();
        $game->board = [
            '0,0' => [['white', 'Q']],
            '0,1' => [['black', 'Q']],
        ];

        $expectedMoves = [
            '1,0', '-1,1'
        ];

        $actualMoves = $queen->getAllValidMoves('0,0', $game);

        sort($expectedMoves);
        sort($actualMoves);

        $this->assertEquals($expectedMoves, $actualMoves);

    }
}
