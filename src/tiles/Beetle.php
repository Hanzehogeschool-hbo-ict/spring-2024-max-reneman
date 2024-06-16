<?php

namespace Hive\tiles;

use Hive\tiles\TileInterface;

class Beetle implements TileInterface
{

    #[\Override] public function isValidMove($from, $to, $game): bool
    {
        return true;
    }


    #[\Override] public function getAllValidMoves($from, $game): array
    {
        // TODO: Implement getAllValidMoves() method.
        return [];
    }

    #[\Override] public function getName(): string
    {
        return "B";
    }
}