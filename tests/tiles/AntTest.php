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

    public function testIsValidMoveRuleA(){

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
}