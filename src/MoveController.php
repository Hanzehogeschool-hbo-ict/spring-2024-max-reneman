<?php

namespace Hive;

// move an existing tile
class MoveController
{
    public function handlePost(string $from, string $to)
    {
        $session = Session::inst();
        $game = $session->get('game');

        $moveCommand = new MoveCommand($from, $to, $session, $game);
        $moveCommand->execute();
    }
}
