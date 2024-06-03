<?php

namespace Hive\tiles;

use Hive\tiles\TileInterface;

class Beetle implements TileInterface
{

    #[\Override] public function isValidMove($from, $to, $game): bool
    {
        return true;
    }


    #[\Override] public function getAllValidMoves(): array
    {
        // TODO: Implement getAllValidMoves() method.
    }

    #[\Override] public function getName(): string
    {
        return "B";
    }
}