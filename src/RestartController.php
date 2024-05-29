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

    public function handleGet(): void
    {
        // Create a new game
        $this->session->set('game', new Game());

        // Get new game id from database
        $this->db->execute('INSERT INTO games VALUES ()');
        $this->session->set('game_id', $this->db->getInsertId());

        // Redirect back to index
        App::redirect();
    }
}
