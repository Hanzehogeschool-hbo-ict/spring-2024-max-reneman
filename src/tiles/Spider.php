<?php

namespace Hive\tiles;

class Spider implements TileInterface
{
    #[\Override] public function isValidMove($from, $to, $game): bool
    {
        // TODO: Implement isValidMove() method.
        /**
         * De spin is nog niet geïmplementeerd. Implementeer de regels om deze steen te
         * bewegen.
         * a. Een spin verplaatst zich door precies drie keer te verschuiven.
         * b. Een verschuiving is een zet zoals de bijenkoningin die mag maken.
         * c. Een spin mag zich niet verplaatsen naar het veld waar hij al staat.
         * d. Een spin mag alleen verplaatst worden over en naar lege velden.
         * e. Een spin mag tijdens zijn verplaatsing geen stap maken naar een veld waar
         *      hij tijdens de verplaatsing al is geweest.
         */
    }

    #[\Override] public function getAllValidMoves(): array
    {
        // TODO: Implement getAllValidMoves() method.
    }

    #[\Override] public function getName(): string
    {
        return "S";
    }
}