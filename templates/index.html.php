<!DOCTYPE html>
<html>
<head>
    <title>Hive</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<div class="board">
    <?php

use Hive\Util;
use Hive\IndexController;
use Hive\Session;
use Hive\Database;

$db = new Database();
$session = new Session();


$width = 35;
$height = 30;

// find minimum values for q and r to render board
$min_q = 1000;
$min_r = 1000;
foreach ($session->get('game')->board as $pos => $tile) {
    $qr = explode(',', $pos);
    if ($qr[0] < $min_q) $min_q = $qr[0];
    if ($qr[1] < $min_r) $min_r = $qr[1];
}

// reduce minimum values for q and r to make room for empty spaces adjacent to tiles
$min_q--;
$min_r--;

// store rendered tiles so they can later be rendered in the proper order
$rendered_tiles = [];

// function to generate tile's HTML
function generateTileHTML($qr, $tile, $min_q, $min_r, $width, $height) {
    $h = count($tile);
    $str = '<div class="tile ';
    if ($tile[$h-1][0] == "&nbsp;") {
        $str .= 'empty';
    } else {
        $str .= 'player' . $tile[$h-1][0];
        if ($h > 1) $str .= ' stacked';
    }
    $str .= '" style="left: ';
    $str .= $width * (($qr[0] - $min_q) + ($qr[1] - $min_r) / 2);
    $str .= 'px; top: ';
    $str .= $height * ($qr[1] - $min_r);
    $str .= "px;\">$qr[0],$qr[1]<span>";
    $str .= $tile[$h-1][1];
    $str .= '</span></div>';
    return $str;
}
// render tiles in play
foreach (array_filter($session->get('game')->board) as $pos => $tile) {
    $qr = explode(',', $pos);
    $rendered_tiles[$pos] = generateTileHTML($qr, $tile, $min_q, $min_r, $width, $height);
}

// render empty tiles adjacent to existing tiles
foreach ($to as $pos) {
    if (!array_key_exists($pos, $session->get('game')->board)) {
        $qr = explode(',', $pos);
        $tile = [["&nbsp;"]];
        $rendered_tiles[$pos] = generateTileHTML($qr, $tile, $min_q, $min_r, $width, $height);
    }
}

// sort in display order
uksort($rendered_tiles, function($a, $b) {
    // split coordinates
    $a = explode(',', $a);
    $b = explode(',', $b);

    // compare second (vertical) coordinate first
    return $a[1] == $b[1] ? $a[0] <=> $b[0] : $a[1] <=> $b[1];
});

// display tiles
foreach ($rendered_tiles as $str) {
    echo($str);
}
?>
</div>
<div class="hand">
    White:
    <?php
    // render tiles in white's hand
    foreach ($session->get('game')->hand[0] as $tile => $ct) {
        for ($i = 0; $i < $ct; $i++) {
            echo '<div class="tile player0"><span>'.$tile."</span></div>";
        }
    }
    ?>
</div>
<div class="hand">
    Black:
    <?php
    // render tiles in black's hand
    foreach ($session->get('game')->hand[1] as $tile => $ct) {
        for ($i = 0; $i < $ct; $i++) {
            echo '<div class="tile player1"><span>'.$tile."</span></div>";
        }
    }
    ?>
</div>
<div class="turn">
    Turn: <?php
        // render active player
        if ($session->get('game')->player == 0) echo "White"; else echo "Black";
    ?>
</div>
<form method="post" action="/play">
    <select name="piece">
        <?php
        // render list of tile types
        $pieces = IndexController::getPieces($session->get('game'));
        foreach ($pieces as $piece){
            echo $piece;
        }
        ?>
    </select>


    <select name="to">
        <?php
        // render list of possible moves
        foreach ($to as $pos) {
            if(!(isset($game->board[$pos]))){
                if(!(count($session->get('game')->board) && !Util::hasNeighBour($pos, $session->get('game')->board))) {
                    if (!(array_sum($session->get('game')->hand[$session->get('game')->player]) < 11 && !Util::neighboursAreSameColor($session->get('game')->player, $pos, $session->get('game')->board)))
                    echo "<option value=\"$pos\">$pos</option>";
                }
            }
        }
        ?>
    </select>
    <input type="submit" value="Play">
</form>
<form method="post" action="/move">

    <select name="from">
        <?php
        // render list of positions in board
        foreach (array_keys($session->get('game')->board) as $pos) {
            if ((!$session->get('game')->board[$pos][count($session->get('game')->board[$pos])-1][0] != $session->get('game')->player)) {
                echo "<option value=\"$pos\">$pos</option>";
            }
        }
        ?>


    </select>
    <select name="to">
        <?php
        // render list of possible moves
        foreach ($to as $pos) {
            echo "<option value=\"$pos\">$pos</option>";
        }
        ?>
    </select>


    <input type="submit" value="Move">
</form>
<form method="post" action="/pass">
    <input type="submit" value="Pass">
</form>
<form method="get" action="/restart">
    <input type="submit" value="Restart">
</form>
<strong><?php
    // render error message
    if (isset($_SESSION['error'])) echo($_SESSION['error']); unset($_SESSION['error']);
?></strong>
<ol>
    <?php
    // render list of moves
    $result = $db->Query("SELECT * FROM moves WHERE game_id = {$session->get('game_id')}");
    while ($row = $result->fetch_array()) {
        echo '<li>'.$row[2].' '.$row[3].' '.$row[4].'</li>';
    }
    ?>
</ol>
<form method="post" action="/undo">
    <input type="submit" value="Undo">
</form>
</body>
</html>

