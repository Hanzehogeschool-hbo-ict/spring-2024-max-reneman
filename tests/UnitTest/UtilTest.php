<?php

namespace UnitTest;

use Hive\Util;
use PHPUnit\Framework\TestCase;


class UtilTest extends TestCase
{

    public function testSlide()
    {
        //arrange
        $gamestate = [["0,0" => [[0, "Q"]], "1,0" => [[1, "Q"]]], [["Q" => 0, "B" => 2, "S" => 2, "A" => 3, "G" => 3], ["Q" => 0, "B" => 2, "S" => 2, "A" => 3, "G" => 3]], 0];;

        //act
        $output = Util::slide($gamestate, "0,0", "0,1");

        //assert

        $this->assertSame($output, true);

    }
}
