<?php

namespace Hive;

class PassController {
    private Session $session;
    private Database $db;

    public function __construct() {
        $this->session = new Session();
        $this->db = new Database();
    }

    public function handlePost(): void
    {
        // get state from session
        $game = $this->session->getFromSession('game');

        // TODO: pass is not implemented yet
        // switch players
        $game->player = 1 - $game->player;

        // store move in database
        $state = $this->db->escape($game);
        $last = $this->session->getFromSession('last_move') ?? 'null';
        $this->db->query("
                insert into moves (game_id, type, move_from, move_to, previous_id, state)
                values ({$this->session->getFromSession('game_id')}, \"pass\", null, null, $last, \"$state\")
            ");
        $this->session->setOnSession('last_move', $this->db->getInsertId());

        // redirect back to index
        App::redirect();
    }
}