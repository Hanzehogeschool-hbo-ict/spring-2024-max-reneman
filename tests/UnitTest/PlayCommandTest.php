<?php

namespace Hive\Tests;

use Hive\PlayCommand;
use Hive\Session;
use Hive\Game;
use Hive\Database;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class PlayCommandTest extends TestCase
{
    private Session $session;
    private Database $db;

    /**
     * @throws Exception
     */
    protected function setUp(): void {
        $this->session = new Session();
        $this->db = $this->createMock(Database::class);
    }

    public function testExecute()
    {
        // Arrange
        $game = new Game();
        $game->player = 0; // White player
        $game->hand[$game->player] = ['Q' => 1, 'B' => 3];
        $this->session->setOnSession('game', $game);

        // Act
        // White player places three non-queen pieces
        $playCommand = new PlayCommand('B', '0,0', $this->session, $game, $this->db);
        $playCommand->execute();
        $playCommand = new PlayCommand('B', '0,1', $this->session, $game, $this->db);
        $playCommand->execute();
        $playCommand = new PlayCommand('B', '0,2', $this->session, $game, $this->db);
        $playCommand->execute();

        // White player tries to place a fourth non-queen piece
        $playCommand = new PlayCommand('B', '0,3', $this->session, $game, $this->db);
        $playCommand->execute();

        // Assert
        $this->assertEquals('Must play queen bee', $this->session->getFromSession('error'));
    }
}