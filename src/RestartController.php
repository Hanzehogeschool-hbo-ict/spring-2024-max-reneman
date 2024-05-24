<?php

namespace Hive;

// restart the game
class RestartController {
    private Session $session;
    private Database $db;

    public function __construct() {
        $this->session = new Session();
        $this->db = new Database();
    }

    public function handleGet() {
        // Create a new game
        $this->session->set('game', new Game());

        // Get new game id from database
        $this->db->Execute('INSERT INTO games VALUES ()');
        $this->session->set('game_id', $this->db->Get_Insert_Id());

        // Redirect back to index
        App::redirect();
    }
}
