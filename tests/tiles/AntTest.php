<?php

namespace tiles;

use Hive\tiles\Ant;
use PHPUnit\Framework\TestCase;
use stdClass;

class AntTest extends TestCase
{
    protected Ant $ant;
    protected stdClass $game;

    protected function setUp(): void
    {
        $this->ant = new Ant();
        $this->game = new stdClass();
        $this->game->board = [];
    }

    public function testGetName()
    {
        $this->assertEquals('A', $this->ant->getName());
    }

    public function testIsValidMoveRuleA(){
        $this->game->board['0,0'] = 'A';
        $this->game->board['1,0'] = 'A';
        $this->game->board['1,1'] = 'A';
        $this->game->board['1,2'] = 'A';
        $this->assertTrue($this->ant->isValidMove('0,0', '0,1', $this->game));
        $this->assertTrue($this->ant->isValidMove('0,0', '0,2', $this->game));
        $this->assertTrue($this->ant->isValidMove('0,0', '0,3', $this->game));

    }
    public function testIsValidMoveRuleBD(){
        $this->game->board['0,0'] = 'A';
        $this->game->board['0,1'] = 'A';
        $this->assertFalse($this->ant->isValidMove('0,0', '0,1', $this->game));
        $this->assertTrue($this->ant->isValidMove('0,0', '0,2', $this->game));
    }
    public function testIsValidMoveRuleC(){
        $this->game->board['0,0'] = 'A';
        $this->assertFalse($this->ant->isValidMove('0,0', '0,0', $this->game));
    }

    public function testGetAllValidMoves()
    {
        $this->game->board['0,0'] = 'A';
        $this->game->board['0,1'] = 'A';

        $expectedMoves = [
            '1,1', '1,0', '0,2', '-1,2', '-1,1'
        ];
        $actualMoves = $this->ant->getAllValidMoves('0,0', $this->game);

        sort($expectedMoves);
        sort($actualMoves);

        $this->assertEquals($expectedMoves, $actualMoves);
    }
    public function testGetAllValidMovesLargeBoard()
    {
        $this->game->board['0,0'] = 'Q';
        $this->game->board['0,1'] = 'Q';
        $this->game->board['0,2'] = 'A';
        $this->game->board['0,3'] = 'A';
        $this->game->board['0,4'] = 'A';
        $this->game->board['0,5'] = 'A';
        $this->game->board['0,6'] = 'A';
        $this->game->board['0,7'] = 'A';
        $this->game->board['0,8'] = 'A';
        $this->game->board['0,9'] = 'A';
        $this->game->board['0,10'] = 'A';
        $this->game->board['0,11'] = 'A';
        $this->game->board['0,12'] = 'A';
        $this->game->board['0,-1'] = 'A';
        $this->game->board['0,-2'] = 'A';
        $this->game->board['0,-3'] = 'A';
        $this->game->board['0,-4'] = 'A';
        $this->game->board['0,-5'] = 'A';
        $this->game->board['0,-6'] = 'A';
        $this->game->board['0,-7'] = 'A';
        $this->game->board['0,-8'] = 'A';
        $this->game->board['0,-9'] = 'A';

        $expectedMoves = [
            '1,1', '1,0', '0,2', '-1,2', '-1,1'
        ];
        $actualMoves = $this->ant->getAllValidMoves('0,12', $this->game);

        sort($expectedMoves);
        sort($actualMoves);

        $this->assertEquals($expectedMoves, $actualMoves);
    }
}