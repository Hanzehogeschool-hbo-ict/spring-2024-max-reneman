<?php

namespace Hive\tiles;

use Hive\Util;
use Override;

class Beetle implements TileInterface
{
    #[Override] public function isValidMove($from, $to, $game): bool
    {
        if ($from === $to) {
            return false;
        }

        // Beetle can move to any neighboring tile
        $neighbors = Util::getAllNeighboringPositions($from);
        if (!in_array($to, $neighbors)) {
            return false;
        }

        return true;
    }

    #[Override] public function getAllValidMoves($from, $game): array
    {
        // Beetle can move to any neighboring tile
        return Util::getAllNeighboringPositions($from);
    }

    #[Override] public function getName(): string
    {
        return "B";
    }
}