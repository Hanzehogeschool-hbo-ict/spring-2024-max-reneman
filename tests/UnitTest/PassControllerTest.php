<?php

namespace UnitTest;

use Hive\Database;
use Hive\Game;
use Hive\Session;
use Hive\PassController;
use PHPUnit\Framework\TestCase;

class PassControllerTest extends TestCase
{
    protected function setUp(): void {
        $this->session = new Session();
        $this->db = $this->createMock(Database::class);
    }

    /**
     * @throws \Exception
     */
    public function testIsPassingAllowed()
    {
        // Arrange
        $game = new Game();

        // Creating a line of tiles from 0, -8 to 0, 13
        $tiles = ['G', 'A', 'A', 'A', 'S', 'S', 'B', 'B', 'Q'];
        $player = 0;
        for ($i = -8; $i <= 0; $i++) {
            $game->board["0,$i"] = [[$tiles[$i +8 ], $player]];
        }

        $tiles = ['Q', 'B', 'B', 'S', 'S', 'A', 'A', 'A', 'G', 'G', 'G'];
        $player = 1;
        for ($i = 1; $i <= 11; $i++) {
            $game->board["0,$i"] = [[$tiles[$i - 1], $player]];
        }

        // last 2 G's belong to the white player
        $player = 0;
        $game->board["0,12"] = [['G', $player]];
        $game->board["0,13"] = [['G', $player]];

        $game->hand[0] = []; // Empty hand for white player
        $game->hand[1] = []; // Empty hand for black player

        $this->session->setOnSession('game', $game);

        // Act
        $game->player = 0;
        $result1 = PassController::isPassingAllowed($game);
        $game->player = 1;
        $result2 = PassController::isPassingAllowed($game);
        // Assert
        $this->assertFalse($result1);
        $this->assertTrue($result2);
    }
}