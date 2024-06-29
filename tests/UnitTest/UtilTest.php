<?php

namespace Tests\Hive;

use PHPUnit\Framework\TestCase;
use Hive\Util;
use Hive\Game;

class UtilTest extends TestCase
{
    private Game $game;

    protected function setUp(): void
    {
        $this->game = new Game();
    }

    public function testSlideToEmptyAdjacentSpace()
    {
        // Arrange
        $this->game->board = [
            "0,0" => [["Q", 0]],
            "1,0" => [["Q", 1]]
        ];

        // Act
        $result = Util::slide($this->game->board, "0,0", "0,1");

        // Assert
        $this->assertTrue($result, "Sliding to an empty adjacent space should be valid");
    }

    public function testSlideToNonAdjacentSpace()
    {
        // Arrange
        $this->game->board = [
            "0,0" => [["Q", 0]],
            "1,0" => [["Q", 1]]
        ];

        // Act
        $result = Util::slide($this->game->board, "0,0", "2,0");

        // Assert
        $this->assertFalse($result, "Sliding to a non-adjacent space should be invalid");
    }

    public function testSlideToDisconnectedSpace()
    {
        // Arrange
        $this->game->board = [
            "0,0" => [["Q", 0]],
            "1,0" => [["Q", 1]]
        ];

        // Act
        $result = Util::slide($this->game->board, "0,0", "-1,0");

        // Assert
        $this->assertFalse($result, "Sliding to a disconnected space should be invalid");
    }

    public function testSlideBetweenHigherStacks()
    {
        // Arrange
        $this->game->board = [
            "0,0" => [["Q", 0]],
            "1,0" => [["A", 1], ["B", 0]],
            "0,1" => [["A", 1], ["B", 0]]
        ];

        // Act
        $result = Util::slide($this->game->board, "0,0", "1,1");

        // Assert
        $this->assertFalse($result, "Sliding between higher stacks should be invalid");
    }

    public function testSlideFromTopOfStack()
    {
        // Arrange
        $this->game->board = [
            "0,0" => [["Q", 0], ["B", 1]],
            "1,0" => [["A", 1]]
        ];

        // Act
        $result = Util::slide($this->game->board, "0,0", "0,1");

        // Assert
        $this->assertTrue($result, "Sliding from top of stack should be valid");
    }

    public function testFixedBugScenario()
    {
        // Arrange
        $this->game->board = [
            "0,0" => [["Q", 0]],
            "1,0" => [["Q", 1]]
        ];

        // Act
        $result = Util::slide($this->game->board, "0,0", "0,1");

        // Assert
        $this->assertTrue($result, "White queen should be able to move from (0,0) to (0,1)");
    }

    public function testSlideToOccupiedSpace()
    {
        // Arrange
        $this->game->board = [
            "0,0" => [["Q", 0]],
            "1,0" => [["Q", 1]],
            "0,1" => [["A", 0]]
        ];

        // Act
        $result = Util::slide($this->game->board, "0,0", "0,1");

        // Assert
        $this->assertTrue($result, "Sliding to an occupied space should be valid (for stacking)");
    }

    public function testSlideFromNonExistentPosition()
    {
        // Arrange
        $this->game->board = [
            "0,0" => [["Q", 0]],
            "1,0" => [["Q", 1]]
        ];

        // Act
        $result = Util::slide($this->game->board, "2,0", "1,1");

        // Assert
        $this->assertFalse($result, "Sliding from a non-existent position should be invalid");
    }
}