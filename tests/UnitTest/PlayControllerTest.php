<?php

namespace Hive\Tests;

use Hive\PlayController;
use Hive\Session;
use Hive\Game;
use PHPUnit\Framework\TestCase;

class PlayControllerTest extends TestCase
{
    public function testHandlePost()
    {
        // Arrange
        $session = Session::inst();
        $game = new Game();
        $game->player = 0; // White player
        $game->hand[$game->player] = ['Q' => 1, 'B' => 3]; // Hand with 1 queen bee and 3 other pieces
        $session->set('game', $game);

        $playController = new PlayController();

        // Act
        // White player places three non-queen pieces
        $playController->handlePost('B', '0,0');
        $playController->handlePost('B', '0,1');
        $playController->handlePost('B', '0,2');

        // White player tries to place a fourth non-queen piece
        $playController->handlePost('B', '0,3');

        // Assert
        $this->assertEquals('Must play queen bee', $session->get('error'));
    }
}