<?php

namespace UnitTest;



use Hive\PlayController;

class PlayControllerTest
{
    public function TestPlayQueenOnFourthMove()
    {
        //arrange
        $gamestate = [["0,0" => [[0, "B"]],"0,1" => [[1, "B"]],"0,-1" => [[0, "B"]],"0,2" => [[1, "B"]],"0,3" => [[1, "Q"]],"0,-2" => [[0, "S"]]], [["Q" => 1, "B" => 0, "S" => 1, "A" => 3, "G" => 3],["Q" => 0, "B" => 0, "S" => 2, "A" => 3, "G" => 3]], 1];

        //act
        $output = PlayController::handlepost("Q", "0,-3");
        $assertinput = [["0,0" => [[0, "B"]],"0,1" => [[1, "B"]],"0,-1" => [[0, "B"]],"0,2" => [[1, "B"]],"0,-2" => [[0, "S"]],"0,3" => [[1, "S"]],"0,-3" => [[0, "Q"]]], [["Q" => 0, "B" => 0, "S" => 1, "A" => 3, "G" => 3],["Q" => 1, "B" => 0, "S" => 1, "A" => 3, "G" => 3]], 1];

        //assert

        $this->assertSame($output, $assertinput);

    }
}