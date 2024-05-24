<?php

namespace Hive;

// play a new tile
class PlayController
{

    public function handlePost(string $piece, string $to)
    {
        $session = Session::inst();
        $game = $session->get('game');

        $playCommand = new PlayCommand($piece, $to, $session, $game);
        $playCommand->execute();
    }

}