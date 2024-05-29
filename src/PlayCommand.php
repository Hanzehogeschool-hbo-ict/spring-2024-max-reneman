<?php

namespace Hive;

class PlayCommand implements CommandInterface
{
private string $piece;
private string $to;
private Session $session;
private Game $game;
private Database $db;

    public function __construct($piece, $to, $session, $game, Database $db)
    {
        $this->piece = $piece;
        $this->to = $to;
        $this->session = $session;
        $this->game = $game;
        $this->db = $db;
    }

    public function execute(): void
    {
        $hand = $this->game->hand[$this->game->player];

        if (!$hand[$this->piece]) {
            $this->session->set('error', "Player does not have tile");
        } elseif (isset($this->game->board[$this->to])) {
            $this->session->set('error', 'Board position is not empty');
        } elseif (count($this->game->board) && !Util::hasNeighBour($this->to, $this->game->board)) {
            $this->session->set('error', "board position has no neighbour");
        } elseif (array_sum($hand) < 11 && !Util::neighboursAreSameColor($this->game->player, $this->to, $this->game->board)) {
            $this->session->set("error", "Board position has opposing neighbour");
        } elseif (array_sum($hand) <= 8 && $this->piece !== 'Q' && $hand['Q']) {
            $this->session->set('error', 'Must play queen bee');
        } else {
            $this->game->board[$this->to] = [[$this->game->player, $this->piece]];
            $this->game->hand[$this->game->player][$this->piece]--;
            $this->game->player = 1 - $this->game->player;
            echo "<script> console.log('" . $this->game->__toString() . "'); </script>";

            $state = $this->db->escape($this->game);
            $last = $this->session->get('last_move') ?? 'null';
            $this->db->execute("
                insert into moves (game_id, type, move_from, move_to, previous_id, state)
                values ({$this->session->get('game_id')}, \"play\", \"$this->piece\", \"$this->to\", $last, \"$state\")
            ");
            $this->session->set('last_move', $this->db->getInsertId());
        }
        App::redirect();
    }
}