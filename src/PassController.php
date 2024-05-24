<?php

namespace Hive;

class PassController {
    private Session $session;
    private Database $db;

    public function __construct() {
        $this->session = new Session();
        $this->db = new Database();
    }

    public function handlePost() {
        // get state from session
        $game = $this->session->get('game');

        // TODO: pass is not implemented yet
        // switch players
        $game->player = 1 - $game->player;

        // store move in database
        $state = $this->db->Escape($game);
        $last = $this->session->get('last_move') ?? 'null';
        $this->db->Query("
                insert into moves (game_id, type, move_from, move_to, previous_id, state)
                values ({$this->session->get('game_id')}, \"pass\", null, null, $last, \"$state\")
            ");
        $this->session->set('last_move', $this->db->Get_Insert_Id());

        // redirect back to index
        App::redirect();
    }
}