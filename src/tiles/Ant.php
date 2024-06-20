<?php

namespace Hive\tiles;
use Hive\Util;
use Override;

class Ant implements TileInterface
{

    #[Override] public function isValidMove($from, $to, $game): bool
    {
        // Rule c: soldatenmier mag zich niet verplaatsen naar het veld waar hij al staat.
        if ($from === $to) {
            return false;
        }

        // Rule d: Een soldatenmier mag alleen verplaatst worden over en naar lege velden.
        if (isset($game->board[$to])) {
            return false;
        }

        // Rule a and b: Een soldatenmier verplaatst zich door een onbeperkt aantal keren te verschuiven
        // Een verschuiving is een zet zoals de bijenkoningin die mag maken.
        // breadth-first search (BFS)
        $visited = [];
        $queue = [$from];

        while (!empty($queue)) {
            $current = array_shift($queue);
            $visited[$current] = true;

            // If we reached the destination, return true.
            if ($current === $to) {
                return true;
            }

            // Get all neighboring positions.
            $neighbors = Util::getNeighboringOccupiedTiles($current, $game->board);

            foreach ($neighbors as $neighbor) {
                // If the neighbor is not visited and is empty, add it to the queue.
                if (!isset($visited[$neighbor]) && !isset($game->board[$neighbor])) {
                    $queue[] = $neighbor;
                }
            }
        }

        // If we didn't find a path to the destination, return false.
        return false;
    }

    #[Override] public function getAllValidMoves($from, $game): array
    {
        $validMoves = [];

        // Use a breadth-first search (BFS) to find all reachable empty fields.
        $visited = [];
        $queue = [$from];

        while (!empty($queue)) {
            $current = array_shift($queue);
            $visited[$current] = true;

            // Get all neighboring positions.
            $neighbors = Util::getNeighboringOccupiedTiles($current, $game->board);

            foreach ($neighbors as $neighbor) {
                echo $neighbor;
                // If the neighbor is not visited and is empty, add it to the queue and to the valid moves.
                // Also check if the neighbor is not already in the queue.
                if (!isset($visited[$neighbor]) && !isset($game->board[$neighbor]) && !in_array($neighbor, $queue)) {
                    $queue[] = $neighbor;
                    $validMoves[] = $neighbor;
                }
            }
        }
        return $validMoves;
    }

    #[Override] public function getName(): string
    {
        return "A";
    }
}