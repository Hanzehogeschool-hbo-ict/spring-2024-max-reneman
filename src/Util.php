<?php

namespace Hive;

// static utility functions
class Util {
    // offsets from a position to its six neighbours
    const array OFFSETS = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

    private function __construct() {}

    // check if both positions are neighbours
    public static function isNeighbour(string $a, string $b): bool
    {
        $a = explode(',', $a);
        $b = explode(',', $b);
        // two tiles are neighbours if their FIRST coordinate is the same and the SECOND one differs by one
        if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) return true;
        // two tiles are also neighbours if their SECOND coordinate is the same and the FIRST one differs by one
        if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1) return true;
        // two tiles are also neighbours if BOTH coordinates differ by one and both DIFFERENCES sum to zero
        // e.g., 0,0 and -1,1 are neigbours
        if ((intval($a[0]) + intval($a[1])) == (intval($b[0]) + intval($b[1]))) return true;
        return false;
    }

    // check if a position has a neighbour already on the board
    public static function hasNeighBour(string $a, array $board) : bool {
        foreach (array_keys($board) as $b) {
            if (self::isNeighbour($a, $b)) return true;
        }
        return false;
    }

    // check if all neighbours of a position belong to the same player
    public static function neighboursAreSameColor(int $player, string $a, array $board): bool
    {
        foreach ($board as $b => $st) {
            if (!$st) continue;
            $c = $st[count($st) - 1][0];
            if ($c != $player && self::isNeighbour($a, $b)) return false;
        }
        return true;
    }

    public static function hasMultipleHivesNewBoard(array $board, $from, $to): bool
    {
        // Remove $from from the board
        unset($board[$from]);

        // Mark $to as filled
        if (!isset($board[$to])) {
            $board[$to] = 1;
        }

        // Use flood fill to find all tiles reachable from a single (essentially random) tile
        // If any tiles are unreachable, the hive is split
        $all = array_keys($board);
        $queue = [array_shift($all)];

        while ($queue) {
            $next = explode(',', array_shift($queue));

            foreach (Util::OFFSETS as $qr) {
                list($q, $r) = $qr;
                $q += intval($next[0]);
                $r += intval($next[1]);

                if (in_array("$q,$r", $all)) {
                    $queue[] = "$q,$r";
                    $all = array_diff($all, ["$q,$r"]);
                }
            }
        }
        return !!$all;
    }
    // check whether a move between two positions is valid given the rules for slides
    // which are used by all tiles except the grasshopper
    public static function slide(array $board, string $from, string $to): bool
    {
        // Check if the 'from' position exists in the board
        if (!isset($board[$from])) return false;

        // Check if 'from' and 'to' are neighbours
        if (!self::isNeighbour($from, $to)) return false;

        // Remove the 'from' position temporarily to check connectivity
        $tempBoard = $board;
        unset($tempBoard[$from]);

        // Check if the 'to' position would be connected to the hive
        if (!self::hasNeighBour($to, $tempBoard)) return false;

        // Find the two common neighbours of the origin and target tiles
        $b = explode(',', $to);
        $common = [];
        foreach (self::OFFSETS as $qr) {
            $q = intval($b[0]) + intval($qr[0]);
            $r = intval($b[1]) + intval($qr[1]);
            if (self::isNeighbour($from, $q.",".$r)) $common[] = $q.",".$r;
        }

        // Get the stacks at the four positions
        $fromStack = $board[$from] ?? [];
        $toStack = $board[$to] ?? [];
        $aStack = $board[$common[0]] ?? [];
        $bStack = $board[$common[1]] ?? [];

        // Check if at least one of the common neighbours is occupied
        if (empty($aStack) && empty($bStack)) return false;

        // Check if the slide is physically possible
        return min(count($aStack), count($bStack)) <= max(count($fromStack), count($toStack));
    }

    public static function getAllNeighboringPositions(mixed $current): array
    {
        $current = explode(',', $current);
        $neighbors = [];
        foreach (self::OFFSETS as $offset) {
            $q = intval($current[0]) + intval($offset[0]);
            $r = intval($current[1]) + intval($offset[1]);
            $position = "$q,$r";
            $neighbors[] = $position;
        }
        return $neighbors;
    }
    public static function maintainsConnectivity($from, $to, $path, $game): bool
    {
        // The move maintains connectivity if the destination has an occupied neighbor
        // that is not the starting position and not in the path
        $neighbors = Util::getNeighboringOccupiedTiles($to, $game->board);
        foreach ($neighbors as $neighbor) {
            if ($neighbor !== $from && !in_array($neighbor, $path)) {
                return true;
            }
        }
        return false;
    }

    public static function getNeighboringOccupiedTiles(mixed $current, array $board): array
    {
        $current = explode(',', $current);
        $neighbors = [];
        foreach (self::OFFSETS as $offset) {
            $q = intval($current[0]) + intval($offset[0]);
            $r = intval($current[1]) + intval($offset[1]);
            $position = "$q,$r";
            // Check if a tile exists at the position
            if (isset($board[$position])) {
                $neighbors[] = $position;
            }
        }
        return $neighbors;
    }
}