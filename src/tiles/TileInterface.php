<?php

namespace Hive\tiles;
interface TileInterface
{
    public function isValidMove($from, $to, $game): bool;
    public function getAllValidMoves(): array;
    public function getName(): string;

}
