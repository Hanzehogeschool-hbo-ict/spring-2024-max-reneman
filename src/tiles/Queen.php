<?php

namespace Hive\tiles;

use Hive\tiles\TileInterface;

class Queen implements TileInterface
{


    #[\Override] public function isValidMove($from, $to, $game): bool
    {
        //$tile = array_pop($game->board[$from]);
        return true;
    }

    #[\Override] public function getAllValidMoves(): array
    {
        // TODO: Implement getAllValidMoves() method.
    }

    #[\Override] public function getName(): string
    {
        return 'Q';
    }
}