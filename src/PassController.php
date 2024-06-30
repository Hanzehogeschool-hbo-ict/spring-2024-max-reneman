<?php

namespace Hive;

use Exception;
use Hive\tiles\Ant;
use Hive\tiles\Beetle;
use Hive\tiles\Grasshopper;
use Hive\tiles\Queen;
use Hive\tiles\Spider;

class PassController {
    private Session $session;
    private Database $db;

    public function __construct() {
        $this->session = new Session();
        $this->db = new Database();
    }

    /**
     * @throws Exception
     */
    public function handlePost(): void
    {
        // get state from session
        $game = $this->session->getFromSession('game');

        //check if passing is allowed
        if ($this->isPassingAllowed($game)) {



            // switch players
            $game->player = 1 - $game->player;

            // store move in database
            $state = $this->db->escape($game);
            $last = $this->session->getFromSession('last_move') ?? 'null';
            $this->db->execute("
        insert into moves (game_id, type, move_from, move_to, previous_id, state)
        values ({$this->session->getFromSession('game_id')}, \"pass\", null, null, $last, \"$state\")
        ");
            $this->session->setOnSession('last_move', $this->db->getInsertId());

            // redirect back to index
            App::redirect();
        }

    else{
        App::redirect();
    }
    }


    public static function isPassingAllowed(Game $game): bool
    {
        // If the player has tiles left in their hand, they cannot pass.
        if (Game::currentPlayerTileAmount($game->player, $game) > 0) {
            return false;
        }


        // If the player has any valid moves they can make with their tiles, they cannot pass.
        foreach ($game->board as $position => $tileStack) {



            $tile = end($tileStack);

            // Check only the current player's tiles.
            if ($tile[0] == $game->player) {

                $tileType = $tile[1];

                $tileObject = match($tileType) {
                    'A' => new Ant(),
                    'B' => new Beetle(),
                    'G' => new Grasshopper(),
                    'Q' => new Queen(),
                    'S' => new Spider(),
                    default => throw new Exception("Unknown tile type: $tileType"),
                };

                // Call the getAllValidMoves method of the corresponding tile object.
                $possibleValidMoves = $tileObject->getAllValidMoves($position, $game);
                $validMoves = [];
                foreach ($possibleValidMoves as $possibleValidMove) {
                    file_put_contents('debug.log', print_r($possibleValidMove, true) . PHP_EOL, FILE_APPEND);
                    if (!Util::hasMultipleHivesNewBoard($game->board, $position , $possibleValidMove)) {
                        $validMoves = $possibleValidMove;
                    }
                }

                if (!$validMoves == []) {
                    return false;
                }
            }
        }
        return true;
    }

}