<?php

namespace UnitTest;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    /**
     * @throws Exception
     */
    public function testRenderTileTypes()
    {
        // Arrange
        $game = $this->createMock(\Hive\Game::class);
        $game->hand = [
            0 => ['A' => 1, 'B' => 0],
            1 => ['C' => 2, 'D' => 1]
        ];
        $game->player = 1;

        // Act

        // render list of tile types
        $pieces = (new \Hive\IndexController)->getPieces($game);

        // Assert
        $this->assertContains('C', $pieces);
        $this->assertContains('D', $pieces);
        $this->assertNotContains('A', $pieces);
        $this->assertNotContains('B', $pieces);
    }
}
