<?php

namespace Hive\tiles;

use Hive\Util;

class Spider implements TileInterface
{
    #[\Override] public function isValidMove($from, $to, $game): bool
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
            $neighbors = Util::getNeighbors($current);

            foreach ($neighbors as $neighbor) {
                // If the neighbor is not visited and is empty, add it to the queue.
                if (!isset($visited[$neighbor]) && !isset($game->board[$neighbor]) && $steps < 3) {
                    $queue[] = [$neighbor, $steps + 1];
                }
            }
        }
        return false;
    }

#[\Override] public function getAllValidMoves($from, $game): array
    {
        $validMoves = [];

        $visited = [];
        $queue = [[$from, 0]];

        while (!empty($queue)) {
            [$current, $steps] = array_shift($queue);
            $visited[$current] = true;

            $neighbors = Util::getNeighbors($current);

            foreach ($neighbors as $neighbor) {
                if (!isset($visited[$neighbor]) && !isset($game->board[$neighbor]) && $steps < 3) {
                    $queue[] = [$neighbor, $steps + 1];
                    if ($steps + 1 === 3) {
                        $validMoves[] = $neighbor;
                    }
                }
            }
        }
        return $validMoves;
    }

    #[\Override] public function getName(): string
    {
        return "S";
    }
}