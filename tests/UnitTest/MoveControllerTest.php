<?php

namespace UnitTest;

use Hive\MoveController;
use Hive\Session;
use PHPUnit\Framework\TestCase;

class MoveControllerTest extends TestCase
{
    public function testHandlePost()
    {
        // Create a new MoveController instance
        $moveController = new MoveController();

        // Initialize a new game
        $game = new \Hive\Game();

        // Set up a game state where a move is possible
        $session = Session::inst();
        $game->board['0,0'] = [[0, 'A']]; // Player 0's Ant at position '0,0'
        $game->board['0,1'] = [[0, 'Q']]; // Player 0's Queen at position '0,1'
        $game->player = 0;
        $session->set('game', $game);

        // Check the initial state of the game board
        $this->assertEquals([[0, 'A']], $game->board['0,0']);
        $this->assertEquals([[0, 'Q']], $game->board['0,1']);

        // Move the Ant from '0,0' to '0,1'
        $moveController->handlePost('0,0', '0,1');

        // Check that the move was successful and that '0,0' is now empty
        $this->assertEmpty($game->board['0,0']);
        $this->assertEquals([[0, 'A'], [0, 'Q']], $game->board['0,1']);

        // Set up a new Beetle that can be legally placed at '0,0'
        $game->hand[$game->player]['B'] = 1; // Player 0 has one Beetle in hand
        $session->set('game', $game);

        // Place the Beetle at '0,0'
        $moveController->handlePost(null, '0,0');

        // Check that the Beetle was successfully placed at '0,0'
        $this->assertEquals([[0, 'B']], $game->board['0,0']);
    }
}