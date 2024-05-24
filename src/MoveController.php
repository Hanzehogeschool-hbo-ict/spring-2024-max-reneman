<?php

namespace Hive;

// move an existing tile
class MoveController
{
    private Session $session;
    private Database $db;

    public function __construct() {
        $this->session = new Session();
        $this->db = new Database();
    }

    public function handlePost(string $from, string $to)
    {

        $game = $this->session->get('game');

        $moveCommand = new MoveCommand($from, $to, $this->session, $game);
        $moveCommand->execute();
    }
}
