<?php

namespace UnitTest;

use Hive\Game;
use Hive\IndexController;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class IndexControllerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetPieces()
    {
        //arrange
        $game = $this->createMock(Game::class);
        $game->hand = [
            0 => ['tile1' => 1, 'tile2' => 0],
            1 => ['tile3' => 2, 'tile4' => 1]
        ];
        $game->player = 0;
        $indexController = new IndexController();

        //act
        $result = $indexController->getPieces($game);

        // Assert
        $expected = ['<option value="tile1">tile1</option>'];
        $this->assertEquals($expected, $result);
    }
}



