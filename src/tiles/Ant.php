<?php

namespace Hive\tiles;

class Ant implements TileInterface
{

    #[\Override] public function isValidMove($from, $to, $game): bool
    {
        // TODO: Implement isValidMove() method.
        /**
         * De soldatenmier is nog niet geïmplementeerd. Implementeer de regels om deze
         * steen te bewegen.
         * a. Een soldatenmier verplaatst zich door een onbeperkt aantal keren te
         *      verschuiven
         * b. Een verschuiving is een zet zoals de bijenkoningin die mag maken.
         * c. Een soldatenmier mag zich niet verplaatsen naar het veld waar hij al staat.
         * d. Een soldatenmier mag alleen verplaatst worden over en naar lege velden.
         */
    }

    #[\Override] public function getAllValidMoves(): array
    {
        // TODO: Implement getAllValidMoves() method.
    }

    #[\Override] public function getName(): string
    {
        return "A";
    }
}