<?php
namespace Hive\tiles;
use Hive\Util;
use Override;

class Ant implements TileInterface
{
    #[Override]
    public function isValidMove($from, $to, $game): bool
    {
        // Rule c: Ant cannot move to its current position
        if ($from === $to) {
            return false;
        }

        // Rule d: Ant can only move to empty fields
        if (isset($game->board[$to])) {
            return false;
        }

        // Perform a breadth-first search (BFS) to find a valid path
        $visited = [];
        $queue = [[$from, []]];

        while (!empty($queue)) {
            [$current, $path] = array_shift($queue);
            $visited[$current] = true;

            if ($current === $to) {
                return true;
            }

            $neighbors = Util::getAllNeighboringPositions($current);
            foreach ($neighbors as $neighbor) {
                if (!isset($visited[$neighbor]) && !isset($game->board[$neighbor])) {
                    $newPath = $path;
                    $newPath[] = $neighbor;
                    // Check if the move maintains connectivity with the hive
                    if (Util::maintainsConnectivity($from, $neighbor, $newPath, $game)) {
                        $queue[] = [$neighbor, $newPath];
                    }
                }
            }
        }

        return false;
    }

    #[Override]
    public function getAllValidMoves($from, $game): array
    {
        $validMoves = [];
        $visited = [];
        $queue = [[$from, []]];

        while (!empty($queue)) {
            [$current, $path] = array_shift($queue);

            if ($current !== $from && !isset($game->board[$current])) {
                $validMoves[] = $current;
            }

            $neighbors = Util::getAllNeighboringPositions($current);
            foreach ($neighbors as $neighbor) {
                if (!isset($visited[$neighbor]) && !isset($game->board[$neighbor])) {
                    $newPath = $path;
                    $newPath[] = $neighbor;
                    if (Util::maintainsConnectivity($from, $neighbor, $newPath, $game)) {
                        $queue[] = [$neighbor, $newPath];
                        $visited[$neighbor] = true;
                    }
                }
            }
        }

        return array_unique($validMoves);
    }

    #[Override]
    public function getName(): string
    {
        return "A";
    }
}