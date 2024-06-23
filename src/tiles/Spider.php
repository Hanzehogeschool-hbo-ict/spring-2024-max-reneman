<?php

namespace Hive\tiles;

use Hive\Util;
use Override;

class Spider implements TileInterface
{
    #[Override] public function isValidMove($from, $to, $game): bool
    {
        // Rule c: Een spin mag zich niet verplaatsen naar het veld waar hij al staat.
        if ($from === $to) {
            return false;
        }

        // Rule d: Een spin mag alleen verplaatst worden over en naar lege velden.
        if (isset($game->board[$to])) {
            return false;
        }

        // Rule a, b and e: Een spin verplaatst zich door precies drie keer te verschuiven.
        // Een verschuiving is een zet zoals de bijenkoningin die mag maken.
        // Een spin mag tijdens zijn verplaatsing geen stap maken naar een veld waar
        // hij tijdens de verplaatsing al is geweest.
        $visited = [];
        $queue = [[$from, 0]];

        while (!empty($queue)) {
            [$current, $steps] = array_shift($queue);
            $visited[$current] = true;

            // If we reached the destination with exactly three steps, return true.
            if ($current === $to && $steps === 3) {
                return true;
            }

            // Get all neighboring positions.
            $neighbors = Util::getAllNeighboringPositions($current);

            foreach ($neighbors as $neighbor) {
                // If the neighbor is not visited and is empty, add it to the queue.
                if (!isset($visited[$neighbor]) && !isset($game->board[$neighbor]) && $steps < 3) {
                    $queue[] = [$neighbor, $steps + 1];
                }
            }
        }
        return false;
    }

    #[Override] public function getAllValidMoves($from, $game): array
    {
        $validMoves = [];
        $visited = [];
        $queue = [[$from, 0, []]];

        while (!empty($queue)) {
            [$current, $steps, $path] = array_shift($queue);

            if ($steps === 3) {
                // Check if this position is a valid move
                if ($this->isValidMove($from, $current, $game)) {
                    $validMoves[] = $current;
                }
                continue; // No need to explore further from this position
            }

            $neighbors = Util::getAllNeighboringPositions($current);

            foreach ($neighbors as $neighbor) {
                if (!isset($visited[$neighbor]) && !isset($game->board[$neighbor]) && $steps < 3) {
                    // Check if moving to this neighbor maintains connectivity
                    $newPath = $path;
                    $newPath[] = $neighbor;
                    if (Util::maintainsConnectivity($from, $neighbor, $newPath, $game)) {
                        $queue[] = [$neighbor, $steps + 1, $newPath];
                        $visited[$neighbor] = true;
                    }
                }
            }
        }

        return array_unique($validMoves);
    }



    #[Override] public function getName(): string
    {
        return "S";
    }
}