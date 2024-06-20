<?php

namespace Hive\tiles;

use Hive\Util;
use Override;

class Queen implements TileInterface
{


    #[Override] public function isValidMove($from, $to, $game): bool
    {
        if ($from === $to) {
            return false;
        }

        if (isset($game->board[$to])) {
            return false;
        }

        $neighbors = Util::getNeighboringTiles($from, $game->board);
        if (!in_array($to, $neighbors)) {
            return false;
        }

        if (!Util::slide($game->board, $from, $to)) {
            return false;
        }

        return true;
    }

    #[Override] public function getAllValidMoves($from, $game): array
    {
        $validMoves = [];

        // Get all neighboring positions.
        $neighbors = Util::getNeighboringTiles($from, $game->board);

        foreach ($neighbors as $neighbor) {
            // If the neighbor is empty and the Queen can slide to it, add it to the valid moves.
            if (!isset($game->board[$neighbor]) && Util::slide($game->board, $from, $neighbor)) {
                $validMoves[] = $neighbor;
            }
        }

        return $validMoves;
    }
    #[Override] public function getName(): string
    {
        return 'Q';
    }
}