<?php

namespace UnitTest;

use Exception;
use Hive\Database;
use Hive\Game;
use Hive\Session;
use Hive\PassController;
use PHPUnit\Framework\TestCase;

class PassControllerTest extends TestCase
{
    protected function setUp(): void {
        $this->session = new Session();
        $this->game = new Game();
        $this->db = $this->createMock(Database::class);
    }

    /**
     * @throws Exception
     */
    public function testIsPassingAllowed()
    {
        // Arrange


        // Creating a line of tiles from 0, -8 to 0, 13
        $tiles = ['G', 'A', 'A', 'A', 'S', 'S', 'B', 'B', 'Q'];
        $player = 0;
        for ($i = -8; $i <= 0; $i++) {
            $this->game->board["0,$i"] = [ $player, [$tiles[$i + 8 ]]];
        }


        $tiles = ['Q', 'B', 'B', 'S', 'S', 'A', 'A', 'A', 'G', 'G', 'G'];
        $player = 1;
        for ($i = 1; $i <= 11; $i++) {
            $this->game->board["0,$i"] = [ $player, [$tiles[$i - 1]]];
        }

        // last 2 G's belong to the white player
        $player = 0;
        $this->game->board["0,12"] = [[$player, 'G']];
        $this->game->board["0,13"] = [[$player, 'G']];
        file_put_contents('debug.log', print_r($this->game->board, true) . PHP_EOL, FILE_APPEND);
        $this->game->hand[0] = [];
        $this->game->hand[1] = []; 

        //$this->session->setOnSession('game', $game);

        // Act
        $this->game->player = 0;
        $result1 = PassController::isPassingAllowed($this->game);
        $this->game->player = 1;
        $result2 = PassController::isPassingAllowed($this->game);
        // Assert
        $this->assertFalse($result1);
        $this->assertTrue($result2);
    }
}