<?php

namespace Hive;

class IndexController {
    private Session $session;
    private Database $db;

    public function __construct(Database $db = null, Session $session = null) {
        $this->db = $db ?? new Database();
        $this->session = $session ?? new Session();
    }

    public function handleGet(): void {
        $game = $this->session->getFromSession('game');
        if (!$game) {
            App::redirect('/restart');
            return;
        }

        $isGameOver = $game->checkIfPlayerLose($game->player);
        //array keys are used in index.html.php
        $viewData = [
            'game' => $game,
            'isGameOver' => $isGameOver,
            'board' => $this->prepareBoard($game),
            'possibleMoves' => $this->getPossibleMoves($game),
            'pieces' => $this->getPieces($game),
            'boardPositions' => $this->getBoardPositions($game),
            'errorMessage' => $this->getErrorMessage(),
            'moveHistory' => $this->getMoveHistory()
        ];

        require_once TEMPLATE_DIR.'/index.html.php';
    }

    private function prepareBoard($game): array {
        $width = 35;
        $height = 30;
        $minCoords = $this->getMinCoordinates($game->board);
        $renderedTiles = [];

        foreach (array_filter($game->board) as $pos => $tile) {
            $qr = explode(',', $pos);
            $renderedTiles[$pos] = $this->generateTileHTML($qr, $tile, $minCoords, $width, $height);
        }

        $possibleMoves = $this->getPossibleMoves($game);
        foreach ($possibleMoves as $pos) {
            if (!array_key_exists($pos, $game->board)) {
                $qr = explode(',', $pos);
                $tile = [["&nbsp;"]];
                $renderedTiles[$pos] = $this->generateTileHTML($qr, $tile, $minCoords, $width, $height);
            }
        }

        uksort($renderedTiles, [$this, 'sortTiles']);

        return $renderedTiles;
    }

    private function getMinCoordinates($board): array {
        $min_q = $min_r = 1000;
        foreach ($board as $pos => $tile) {
            $qr = explode(',', $pos);
            $min_q = min($min_q, $qr[0]);
            $min_r = min($min_r, $qr[1]);
        }
        return ['q' => $min_q - 1, 'r' => $min_r - 1];
    }

    private function generateTileHTML($qr, $tile, $minCoords, $width, $height): string {
        $h = count($tile);
        $class = ($tile[$h-1][0] == "&nbsp;") ? 'empty' : 'player' . $tile[$h-1][0] . ($h > 1 ? ' stacked' : '');
        $left = $width * (($qr[0] - $minCoords['q']) + ($qr[1] - $minCoords['r']) / 2);
        $top = $height * ($qr[1] - $minCoords['r']);
        $content = $tile[$h - 1][1] ?? "";

        return "<div class=\"tile $class\" style=\"left: {$left}px; top: {$top}px;\">$qr[0],$qr[1]<span>$content</span></div>";
    }

    private function sortTiles($a, $b): int
    {
        $a = explode(',', $a);
        $b = explode(',', $b);
        return $a[1] == $b[1] ? $a[0] <=> $b[0] : $a[1] <=> $b[1];
    }

        private function getPossibleMoves($game): array {
            $to = [];
            foreach (Util::OFFSETS as $qr) {
                foreach (array_keys($game->board) as $pos) {
                    $qr2 = explode(',', $pos);
                    $to[] = intval($qr[0]) + intval($qr2[0]) .','. intval($qr[1]) + intval($qr2[1]);
                }
            }
            $to = array_unique($to);
            return count($to) ? $to : ['0,0'];
        }

    public function getPieces($game): array {
        $return = [];
        foreach ($game->hand[$game->player] as $tile => $ct) {
            if($ct !== 0) $return[] = "<option value=\"$tile\">$tile</option>";
        }
        return $return;
    }

    private function getBoardPositions($game): array {
        $positions = [];
        foreach (array_keys($game->board) as $pos) {
            if ($game->board[$pos][count($game->board[$pos])-1][0] == $game->player) {
                $positions[] = "<option value=\"$pos\">$pos</option>";
            }
        }
        return $positions;
    }

    private function getErrorMessage(): ?string {
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
            return $error;
        }
        return null;
    }

    private function getMoveHistory(): array {
        $moves = [];
        $result = $this->db->query("SELECT * FROM moves WHERE game_id = {$this->session->getFromSession('game_id')}");
        while ($row = $result->fetch_array()) {
            $moves[] = "$row[2] $row[3] $row[4]";
        }
        return $moves;
    }
}