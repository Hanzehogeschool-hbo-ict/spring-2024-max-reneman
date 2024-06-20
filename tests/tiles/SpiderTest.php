<?php

namespace tiles;

use stdClass;
use Hive\tiles\Spider;
use PHPUnit\Framework\TestCase;

class SpiderTest extends TestCase
{
    protected function setUp(): void
    {
        $this->spider = new Spider();
        $this->game = new stdClass();
        $this->game->board = [
            '0,0' => [['white', 'S']]
        ];
    }

    public function testIsValidMoveRuleCD()
    {
        // Rule c: Een spin mag zich niet verplaatsen naar het veld waar hij al staat.
        $this->assertFalse($this->spider->isValidMove('0,0', '0,0', $this->game));
        // Rule d: Een spin mag alleen verplaatst worden over en naar lege velden.
        $this->game->board['0,1'] = [['white', 'S']];
        $this->assertFalse($this->spider->isValidMove('0,0', '0,1', $this->game));

    }

    public function testIsValidMoveRuleABE()
    {
        // Rule a, b and e: Een spin verplaatst zich door precies drie keer te verschuiven.
        // Een verschuiving is een zet zoals de bijenkoningin die mag maken.
        // Een spin mag tijdens zijn verplaatsing geen stap maken naar een veld waar
        // hij tijdens de verplaatsing al is geweest.
        $this->assertTrue($this->spider->isValidMove('0,0', '0,3', $this->game));

    }

    public function testSpiderDoesNotBacktrack()
    {
        $this->game->board = [
            '0,1' => [['white', 'S']],
            '0,2' => [['white', 'S']],
            '1,0' => [['white', 'S']],
            '1,2' => [['white', 'S']],
            '2,0' => [['white', 'S']],
            '2,2' => [['white', 'S']],
        ];

        // ik heb een bord gemaakt die er zo uitziet:
        //        S - S - S
        //        | \ | / |
        //        S - x - S
        //        | / | \ |
        //        S - x - S
        // met het idee om van mid boven naar mid mid te bewegen maar dat kan niet met 3 stappen en zonder
        // terug te gaan naar een veld waar je al bent geweest
        $this->assertFalse($this->spider->isValidMove('0,1', '1,1', $this->game));
    }

    public function testGetAllValidMoves()
    {
        $this->game->board = [
            '0,0' => null,
            '1,2' => [['white', 'S']],
            '2,2' => [['white', 'Q']],
            '3,2' => [['white', 'Q']],
            '4,2' => [['white', 'S']]
        ];

        $expectedMoves = [
            '4,1', '4,3'
        ];

        $actualMoves = $this->spider->getAllValidMoves('1,2', $this->game);

        sort($expectedMoves);
        sort($actualMoves);

        $this->assertEquals($expectedMoves, $actualMoves);
    }

    public function testGetName()
    {
        $this->assertEquals('S', $this->spider->getName());
    }
}