<?php

namespace tiles;

use Hive\tiles\GrassHopper;
use PHPUnit\Framework\TestCase;

class GrasshopperTest extends TestCase
{
    protected GrassHopper $grassHopper;
    protected \stdClass $game;

    protected function setUp(): void
    {
        $this->grassHopper = new GrassHopper();
        $this->game = new \stdClass();
        $this->game->board = [];
        $this->game->board['0,0'] = [['white', 'G']];
    }

    public function testGetName()
    {
        $this->assertEquals('G', $this->grassHopper->getName());
    }

    public function testIsValidMoveRuleB()
    {
        // Rule b. Een sprinkhaan mag zich niet verplaatsen naar het veld waar hij al staat.
        $this->assertFalse($this->grassHopper->isValidMove('0,0', '0,0', $this->game));


    }
    public function testIsValidMoveRuleC()
    {
        // Rule c. Een sprinkhaan moet over minimaal één steen springen.
        $this->assertFalse($this->grassHopper->isValidMove('0,0', '0,1', $this->game));
    }
    public function testGetAllValidMovesD()
    {
        // Rule D. Een sprinkhaan mag niet naar een plek die al bezet is
        $this->game->board['0,1'] = [['white', 'S']];
        $this->assertFalse($this->grassHopper->isValidMove('0,0', '0,1', $this->game));
    }

    public function testIsValidMoveRuleE()
    {
        // Rule e. Een sprinkhaan mag niet over lege velden springen. Dit betekent dat alle
        // velden tussen de start- en eindpositie bezet moeten zijn.
        $this->game->board['0,1'] = [['white', 'S']];
        $this->game->board['0,3'] = [['white', 'S']];
        $this->assertFalse($this->grassHopper->isValidMove('0,0', '0,4', $this->game));


        $this->game->board['0,2'] = [['white', 'S']];
        $this->assertTrue($this->grassHopper->isValidMove('0,0', '0,4', $this->game));


    }

    public function testGetAllValidMoves()
    {
        $this->game->board['0,1'] = [['white', 'Q']];

        $this->game->board['0,-1'] = [['white', 'Q']];

        $this->game->board['1,0'] = [['white', 'Q']];
        $this->game->board['2,0'] = [['white', 'Q']];
        $this->game->board['3,0'] = [['white', 'Q']];

        $this->game->board['2,-2'] = [['white', 'Q']];
        unset($this->game->board['1,-1']);


        $expectedMoves = [
            '0,2','4,0','0,-2'
        ];
        $actualMoves = $this->grassHopper->getAllValidMoves('0,0', $this->game);

        sort($expectedMoves);
        sort($actualMoves);


        $this->assertEquals($expectedMoves, $actualMoves);
    }

}