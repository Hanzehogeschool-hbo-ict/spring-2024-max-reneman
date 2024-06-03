<?php

namespace Hive\tiles;

class Grasshopper implements TileInterface
{
    #[\Override] public function isValidMove($from, $to, $game): bool
    {
        // TODO: Implement isValidMove() method.
        /**
         * De sprinkhaan is nog niet geïmplementeerd. Implementeer de regels om deze
         * steen te bewegen.
         * a. Een sprinkhaan verplaatst zich door in een rechte lijn een sprong te maken
         *      naar een veld meteen achter een andere steen in de richting van de sprong.
         * b. Een sprinkhaan mag zich niet verplaatsen naar het veld waar hij al staat.
         * c. Een sprinkhaan moet over minimaal één steen springen.
         * d. Een sprinkhaan mag niet naar een bezet veld springen.
         * e. Een sprinkhaan mag niet over lege velden springen. Dit betekent dat alle
         *      velden tussen de start- en eindpositie bezet moeten zijn.
         */
    }

    #[\Override] public function getAllValidMoves(): array
    {
        // TODO: Implement getAllValidMoves() method.
    }

    #[\Override] public function getName(): string
    {
        return "G";
    }
}