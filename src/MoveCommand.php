<?php

namespace Hive;

use Hive\tiles\Beetle;
use Hive\tiles\Queen;

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
        if (!$this->isPositionNotEmpty()) {
            $this->session->setOnSession('error', 'Board position is empty');
            return false;
        }

        if (!$this->isTileOwnedByPlayer()) {
            $this->session->setOnSession("error", "Tile is not owned by player");
            return false;
        }

        if ($this->isQueenBeeNotPlayed()) {
            $this->session->setOnSession('error', "Queen bee is not played");
            return false;
        }

        if ($this->isSamePosition()) {
            $this->session->setOnSession('error', 'Tile must move to a different position');
            return false;
        }

        //TODO check current tile movement rules
        if (!Queen::class->isValidMove($this->from, $this->to, $this->game) ){
            $this->session->setOnSession('error', 'Invalid Queen move');
            return false;
        }

        if (!Beetle::class->isValidMove()){
            $this->session->setOnSession('error', 'Invalid Beetle move');
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

        if (Util::hasMultipleHives($this->game->board)) {
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