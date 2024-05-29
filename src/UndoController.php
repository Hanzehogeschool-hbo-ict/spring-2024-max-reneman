<?php

namespace Hive;

// undo last move
class UndoController
{

    public function __construct() {
        $this->session = new Session();
        $this->db = new Database();
    }
    public function handlePost(): void
    {
        // restore last move from database
        $last_move = $this->session->get('last_move') ?? 0;
        $result = $this->db>Query("SELECT previous_id, state FROM moves WHERE id = $last_move")->fetch_array();
        $this->session->set('last_move', $result[0]);
        $this->session->set('game', Game::fromString($result[1]));

        // redirect back to index
        App::redirect();
    }
}
