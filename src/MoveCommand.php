<?php

namespace Hive;

use InvalidArgumentException;

class MoveCommand implements CommandInterface
{
    private string $from;
    private string $to;
    private Session $session;
    private Game $game;
    private Database $db;

    public function __construct($from, $to, $session, $game, Database $db)
    {
        $this->from = $from;
        $this->to = $to;
        $this->session = $session;
        $this->game = $game;
        $this->db = $db;
    }


    public function execute(): void
    {
        if ($this->validateMove()) {
            $this->performMove();
        }

        App::redirect();
    }
    private function validateMove(): bool
    {
        $selected_tile = end($this->game->board[$this->from]);

        $tile_type = $selected_tile[1];

        if (!$this->isPositionNotEmpty()) {
            $this->session->setOnSession('error', 'Board position is empty');
            return false;
        }

        if (!$this->isTileOwnedByPlayer()) {
            $this->session->setOnSession("error", "Tile is not owned by player");
            return false;
        }

        if ($this->isQueenBeeNotPlayed() and Game::currentPlayerTileAmount($this->game->player)<8  ) {
            $this->session->setOnSession('error', "Queen bee is not played");
            return false;
        }

        if ($this->isSamePosition()) {
            $this->session->setOnSession('error', 'Tile must move to a different position');
            return false;
        }

        //TODO check current tile movement rules
        $isValid = match ($tile_type) {
            'Q' => (new tiles\Queen)->isValidMove($this->from, $this->to, $this->game),
            'B' => (new tiles\Beetle)->isValidMove($this->from, $this->to, $this->game),
            'S' => (new tiles\Spider)->isValidMove($this->from, $this->to, $this->game),
            'A' => (new tiles\Ant)->isValidMove($this->from, $this->to, $this->game),
            'G' => (new tiles\Grasshopper)->isValidMove($this->from, $this->to, $this->game),
            default => throw new InvalidArgumentException("Invalid tile type: $tile_type"),
        };

        //$output = (new tiles\Queen)->getAllValidMoves($this->from, $this->game);
        //file_put_contents('debug.log', print_r($output, true) . PHP_EOL, FILE_APPEND);

        //foreach ($output as $move) {
        //    file_put_contents('debug.log', print_r($move, true) . PHP_EOL, FILE_APPEND);
        //}

        if (!$isValid) {
            $this->session->setOnSession('error', "Invalid $tile_type move");
            return false;
        }


        return true;
    }

    private function performMove(): void
    {
        $tile = array_pop($this->game->board[$this->from]);
        if (empty($this->game->board[$this->from])) {
            unset($this->game->board[$this->from]);
        }

        if (!$this->isValidTileMove($tile)) {
            $this->game->board[$this->from][] = $tile;
        } else {
            $this->finalizeMove($tile);
        }
    }

    private function isValidTileMove(array $tile): bool
    {
        if (!Util::hasNeighBour($this->to, $this->game->board)) {
            $this->session->setOnSession("error", "Move would split hive");
            return false;
        }

        if (Util::hasMultipleHivesNewBoard($this->game->board,$this->from,$this->to)) {
            $this->session->setOnSession("error", "Move would split hive");
            return false;
        }

        if (isset($this->game->board[$this->to]) && $tile[1] != "B") {
            $this->session->setOnSession("error", 'Tile not empty');
            return false;
        }

        if ($tile[1] == "Q" || $tile[1] == "B") {
            if (!Util::slide($this->game->board, $this->from, $this->to)) {
                $this->session->setOnSession("error", 'Tile must slide');
                return false;
            }
        }

        return true;
    }

    private function finalizeMove(array $tile): void
    {
        if (isset($this->game->board[$this->to])) {
            $this->game->board[$this->to][] = $tile;
        } else {
            $this->game->board[$this->to] = [$tile];
        }

        $this->game->player = 1 - $this->game->player;
        $state = $this->db->escape($this->game);
        $last = $this->session->getFromSession('last_move') ?? 'null';
        $this->db->execute("
            insert into moves (game_id, type, move_from, move_to, previous_id, state)
            values ({$this->session->getFromSession('game_id')}, \"move\", \"$this->from\", \"$this->to\", $last, \"$state\")
        ");
        $this->session->setOnSession('last_move', $this->db->getInsertId());
    }

    private function isPositionNotEmpty(): bool
    {
        return isset($this->game->board[$this->from]);
    }

    private function isTileOwnedByPlayer(): bool
    {
        return $this->game->board[$this->from][count($this->game->board[$this->from]) - 1][0] == $this->game->player;
    }

    private function isQueenBeeNotPlayed(): bool
    {
        $hand = $this->game->hand[$this->game->player];
        return $hand['Q'];
    }

    private function isSamePosition(): bool
    {
        return $this->from === $this->to;
    }
}