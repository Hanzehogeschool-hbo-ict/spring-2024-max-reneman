<?php

namespace Hive;

// undo last move
use AllowDynamicProperties;

#[AllowDynamicProperties] class UndoController
{

    public function __construct() {
        $this->session = new Session();
        $this->db = new Database();
    }
    public function handlePost(): void
    {
        if (!$this->session->getFromSession('game')->hasAnyPlayerPlayedTile()) {
            App::redirect();
            return;
        }

        // restore last move from database
        $last_move = $this->session->getFromSession('last_move') ?? 0;
        $result = $this->db->query("SELECT previous_id, state FROM moves WHERE id = $last_move")->fetch_array();
        $this->session->setOnSession('last_move', $result[0]);
        $this->session->setOnSession('game', Game::fromString($result[1]));

        // redirect back to index
        App::redirect();
    }
}
