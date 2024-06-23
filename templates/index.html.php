<!DOCTYPE html>
<html>
<head>
    <title>Hive</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<div class="board">
    <?php use Hive\Util;

    foreach ($viewData['board'] as $tile): ?>
        <?= $tile ?>
    <?php endforeach; ?>
</div>
<div class="hand">
    White:
    <?php foreach ($viewData['game']->hand[0] as $tile => $ct): ?>
        <?php for ($i = 0; $i < $ct; $i++): ?>
            <div class="tile player0"><span><?= $tile ?></span></div>
        <?php endfor; ?>
    <?php endforeach; ?>
</div>
<div class="hand">
    Black:
    <?php foreach ($viewData['game']->hand[1] as $tile => $ct): ?>
        <?php for ($i = 0; $i < $ct; $i++): ?>
            <div class="tile player1"><span><?= $tile ?></span></div>
        <?php endfor; ?>
    <?php endforeach; ?>
</div>
<div class="turn">
    Turn: <?= $viewData['game']->player == 0 ? "White" : "Black" ?>
</div>
<form method="post" action="/play">
    <select name="piece">
        <?php foreach ($viewData['pieces'] as $piece): ?>
            <?= $piece ?>
        <?php endforeach; ?>
    </select>
    <select name="to">
        <?php foreach ($viewData['possibleMoves'] as $pos): ?>
            <?php if (!isset($viewData['game']->board[$pos]) &&
                (!count($viewData['game']->board) || Util::hasNeighBour($pos, $viewData['game']->board)) &&
                (array_sum($viewData['game']->hand[$viewData['game']->player]) >= 11 || Util::neighboursAreSameColor($viewData['game']->player, $pos, $viewData['game']->board))): ?>
                <option value="<?= $pos ?>"><?= $pos ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Play">
</form>
<form method="post" action="/move">
    <select name="from">
        <?php foreach ($viewData['boardPositions'] as $position): ?>
            <?= $position ?>
        <?php endforeach; ?>
    </select>
    <select name="to">
        <?php foreach ($viewData['possibleMoves'] as $pos): ?>
            <option value="<?= $pos ?>"><?= $pos ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Move">
</form>
<form method="post" action="/pass">
    <input type="submit" value="Pass">
</form>
<form method="get" action="/restart">
    <input type="submit" value="Restart">
</form>
<?php if ($viewData['errorMessage']): ?>
    <strong><?= $viewData['errorMessage'] ?></strong>
<?php endif; ?>
<ol>
    <?php foreach ($viewData['moveHistory'] as $move): ?>
        <li><?= $move ?></li>
    <?php endforeach; ?>
</ol>
<form method="post" action="/undo">
    <input type="submit" value="Undo">
</form>
</body>
</html>