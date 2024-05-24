<?php

namespace Hive\Tests;

use Hive\PlayController;
use Hive\Session;
use Hive\Game;
use PHPUnit\Framework\TestCase;

class PlayControllerTest extends TestCase
{
    private Session $session;
    private PlayController $playController;

    protected function setUp(): void {
        $this->session = new Session();
        $this->playController = new PlayController($this->session);
    }

    public function testHandlePost()
    {
        // Arrange
        $game = new Game();
        $game->player = 0; // White player
        $game->hand[$game->player] = ['Q' => 1, 'B' => 3]; // Hand with 1 queen bee and 3 other pieces
        $this->session->set('game', $game);

        // Act
        // White player places three non-queen pieces
        $this->playController->handlePost('B', '0,0');
        $this->playController->handlePost('B', '0,1');
        $this->playController->handlePost('B', '0,2');

        // White player tries to place a fourth non-queen piece
        $this->playController->handlePost('B', '0,3');

        // Assert
        $this->assertEquals('Must play queen bee', $this->session->get('error'));
    }
}