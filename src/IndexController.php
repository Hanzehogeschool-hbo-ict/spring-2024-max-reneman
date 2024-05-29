<?php

namespace Hive;

/**
 * Handle index page.
 */
class IndexController
{
    private Session $session;

    public function __construct() {
        $this->session = new Session();
    }





    public function handleGet(): void
    {
        $session = $this->session->get('game');

        // ensure session contains a game
        $game = $this->session->get('game');
        if (!$game) {
            App::redirect('/restart');
            return;
        }

        // find all positions that are adjacent to one of the tiles in the hive as candidates for a new tile
        $to = [];
        foreach (Util::OFFSETS as $qr) {
            foreach (array_keys($game->board) as $pos) {
                $qr2 = explode(',', $pos);
                $to[] = intval($qr[0]) + intval($qr2[0]) .','. intval($qr[1]) + intval($qr2[1]);
            }
        }
        $to = array_unique($to);
        if (!count($to)) $to[] = '0,0';

        // render view
        require_once TEMPLATE_DIR.'/index.html.php';
    }

    public function getPieces($game): array
    {
        $return = [];
        foreach ($game->hand[$game->player] as $tile => $ct) {
            if($ct !==0)
                $return[] = "<option value=\"$tile\">$tile</option>";
        }
        return $return;
    }
}