<?php

namespace Hive;

// play a new tile
class PlayController
{
    private Session $session;
    private Database $db;

    public function __construct()
    {
        $this->session = new Session();
        $this->db = new Database();
    }

    public function handlePost(string $piece, string $to): void
    {

        $game = $this->session->getFromSession('game');

        $playCommand = new PlayCommand($piece, $to, $this->session, $game, $this->db);
        $playCommand->execute();
    }

}