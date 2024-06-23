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
        foreach (self::OFFSETS as $offset) {
            [$hopDestination, $isValid] = $this->getPath($from, $offset, $game->board);

            if ($hopDestination === $to && $isValid) {
                return true;
            }
        }

        return false;
    }

    private function getPath($from, $offset, $board): array
    {
        list($fromX, $fromY) = array_map('intval', explode(',', $from));

        list($dx, $dy) = $offset;

        $x = $fromX;
        $y = $fromY;

        $hasHopped = false;
        $allOccupied = true;

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
                return ["$x,$y", $allOccupied];
            }
            else {
                $allOccupied = false;
            }

            // Reached the border of the game tiles. Exit the loop.
            if (abs($x - $fromX) > 100 || abs($y - $fromY) > 100) {
                break;
            }
        }

        return [null, false];
    }

    #[Override]
    public function getAllValidMoves($from, $game): array
    {
        $validMoves = [];

        foreach (self::OFFSETS as $offset) {
            [$to, $isValid] = $this->getPath($from, $offset, $game->board);
            if ($to !== null && $isValid) {
                $validMoves[] = $to;
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