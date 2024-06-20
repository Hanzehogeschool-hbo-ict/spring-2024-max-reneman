<?php

namespace Hive\tiles;

use Override;

class Grasshopper implements TileInterface
{
    const array OFFSETS = [
        [1, 0], // East
        [-1, 0], // West
        [0, -1],  // North
        [0, 1],  // South
        [1, -1], // Northeast
        [-1, 1],  // Southwest
    ];

    #[Override]
    public function isValidMove($from, $to, $game): bool
    {
        $directions = [
            // East
            [1, 0],
            // West
            [-1, 0],
            // North
            [0, -1],
            // South
            [0, 1],
            // Northeast
            [1, -1],
            // Southwest
            [-1, 1]
        ];

        foreach ($directions as $offset) {
            $hopDestination = $this->getPath($from, $offset, $game->board);

            // If the hop destination matches the target and a hop has occurred.
            if ($hopDestination === $to) {
                return true;
            }
        }

        return false;
    }

    private function getPath($from, $offset, $board): ?string
    {
        list($fromX, $fromY) = array_map('intval', explode(',', $from));

        list($dx, $dy) = $offset;

        $x = $fromX;
        $y = $fromY;

        $hasHopped = false;

        // Continue moving in the same direction until an unoccupied tile is found.
        while (true) {
            $x += $dx;
            $y += $dy;

            // Check for occupied tile
            if (isset($board["$x,$y"])) {
                $hasHopped = true;
            }
            // Check for unoccupied tile and that it has hopped at least once.
            elseif (!isset($board["$x,$y"]) && $hasHopped) {
                return "$x,$y";
            }

            // Reached the border of the game tiles. Exit the loop.
            if (abs($x - $fromX) > 100 || abs($y - $fromY) > 100) {
                break;
            }
        }

        return null;
    }

    #[Override]
    public function getAllValidMoves($from, $game): array
    {
        $validMoves = [];

        foreach (self::OFFSETS as $offset) {
            $to = $this->getPath($from, [$from[0] + $offset[0], $from[1] + $offset[1]], $offset);
            if ($to !== null && $this->isValidMove($from, $to[0], $game)) {
                $validMoves[] = $to[0];
            }
        }

        return $validMoves;
    }

    #[Override]
    public function getName(): string
    {
        return "G";
    }
}