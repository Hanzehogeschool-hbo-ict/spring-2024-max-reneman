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
        // a slide is only valid if from and to are neighbours and to connects to the remainder of the hive
        if (!self::hasNeighBour($to, $board)) return false;
        if (!self::isNeighbour($from, $to)) return false;

        // find the two common neighbours of the origin and target tiles
        // there are always two, because the two tiles are neighbours
        $b = explode(',', $to);
        $common = [];
        foreach (self::OFFSETS as $qr) {
            $q = intval($b[0]) + intval($qr[0]);
            $r = intval($b[1]) + intval($qr[1]);
            if (self::isNeighbour($from, $q.",".$r)) $common[] = $q.",".$r;
        }

        // find the stacks at the four positions
        $from = $board[$from] ?? [];
        $to = $board[$to] ?? [];
        $a = $board[$common[0]] ?? [];
        $b = $board[$common[1]] ?? [];

        // if none of these four stacks contain tiles, the tile would be disconnected from
        // the hive during the move and the slide would therefore be invalid
        // but if at least one of them contains tiles, the move should be valid
//        if (!$a || !$b || !$from || !$to) return false;

        // the rules are unclear on when exactly a slide is valid, especially when considering stacked tiles
        // the following equation attempts to clarify which slides are valid
        // essentially, a slide is valid if the highest of the stacks at origin and target are at least as
        // high as the lowest stack at the two common neighbours, because that would allow the moving tile
        // to physically slide to the target location without having to squeeze between two tiles
        return min(count($a), count($b)) <= max(count($from), count($to));
    }

    public static function getNeighboringOccupiedTiles(mixed $current, array $board): array
    {
        $current = explode(',', $current);
        $neighbors = [];
        foreach (self::OFFSETS as $offset) {
            $q = $current[0] + $offset[0];
            $r = $current[1] + $offset[1];
            $position = "$q,$r";
            // Check if a tile exists at the position
            if (isset($board[$position])) {
                $neighbors[] = $position;
            }
        }
        return $neighbors;
    }

    public static function getAllNeighboringPositions(mixed $current): array
    {
        $current = explode(',', $current);
        $neighbors = [];
        foreach (self::OFFSETS as $offset) {
            $q = $current[0] + $offset[0];
            $r = $current[1] + $offset[1];
            $position = "$q,$r";
            $neighbors[] = $position;
        }
        return $neighbors;
    }
}