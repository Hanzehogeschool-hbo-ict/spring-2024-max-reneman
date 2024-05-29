<?php

namespace Hive;

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
        $hand = $this->game->hand[$this->game->player];

        if (!isset($this->game->board[$this->from])) {
            $this->session->set('error', 'Board position is empty');
        } elseif ($this->game->board[$this->from][count($this->game->board[$this->from]) - 1][0] != $this->game->player) {
            $this->session->set("error", "Tile is not owned by player");
        } elseif ($hand['Q']) {
            $this->session->set('error', "Queen bee is not played");
        } elseif ($this->from === $this->to) {
            $this->session->set('error', 'Tile must move to a different position');
        } else {
            $tile = array_pop($this->game->board[$this->from]);
            if (empty($this->game->board[$this->from])) {
                unset($this->game->board[$this->from]);
            }

            if (!Util::hasNeighBour($this->to, $this->game->board)) {
                $this->session->set("error", "Move would split hive");
            } elseif (Util::hasMultipleHives($this->game->board)) {
                $this->session->set("error", "Move would split hive");
            } elseif (isset($this->game->board[$this->to]) && $tile[1] != "B") {
                $this->session->set("error", 'Tile not empty');
            } elseif ($tile[1] == "Q" || $tile[1] == "B") {
                if (!Util::slide($this->game->board, $this->from, $this->to))
                    $this->session->set("error", 'Tile must slide');
            }
            if ($this->session->get('error')) {
                if (isset($this->game->board[$this->from])) $this->game->board[$this->from][] = $tile;
                else $this->game->board[$this->from] = [$tile];
            } else {
                if (isset($this->game->board[$this->to])) $this->game->board[$this->to][] = $tile;
                else $this->game->board[$this->to] = [$tile];
                $this->game->player = 1 - $this->game->player;
                $state = $this->db->escape($this->game);
                $last = $this->session->get('last_move') ?? 'null';
                $this->db->execute("
                insert into moves (game_id, type, move_from, move_to, previous_id, state)
                values ({$this->session->get('game_id')}, \"move\", \"$this->from\", \"$this->to\", $last, \"$state\")
            ");
                $this->session->set('last_move', $this->db->getInsertId());

            }
        }
        App::redirect();
    }
}