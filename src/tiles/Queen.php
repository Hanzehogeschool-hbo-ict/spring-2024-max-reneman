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

        $neighbors = Util::getAllNeighboringPositions($from);
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

        $neighbors = Util::getAllNeighboringPositions($from);

        foreach ($neighbors as $neighbor) {
            if ($this->isValidMove($from, $neighbor, $game)) {
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