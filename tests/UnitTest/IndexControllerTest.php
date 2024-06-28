<?php

namespace Tests\Hive;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Hive\IndexController;
use Hive\Game;
use Hive\Database;
use PHPUnit\Framework\MockObject\MockObject;

class IndexControllerTest extends TestCase
{
    private IndexController $controller;
    private Game $game;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $dbMock = $this->createMock(Database::class);
        $this->controller = new IndexController($dbMock);
        $this->game = new Game();
    }

    public function testGetPiecesReturnsEmptyArrayWhenPlayerHasNoTiles()
    {
        // Arrange
        $this->game->hand[$this->game->player] = ["Q" => 0, "B" => 0, "S" => 0, "A" => 0, "G" => 0];

        // Act
        $result = $this->controller->getPieces($this->game);

        // Assert
        $this->assertEmpty($result);
    }

    public function testGetPiecesReturnsCorrectOptionsForAvailableTiles()
    {
        // Arrange
        $this->game->hand[$this->game->player] = ["Q" => 1, "B" => 2, "S" => 0, "A" => 1, "G" => 0];

        // Act
        $result = $this->controller->getPieces($this->game);

        // Assert
        $this->assertCount(3, $result);
        $this->assertContains('<option value="Q">Q</option>', $result);
        $this->assertContains('<option value="B">B</option>', $result);
        $this->assertContains('<option value="A">A</option>', $result);
    }

    public function testGetPiecesDoesNotReturnOptionsForUnavailableTiles()
    {
        // Arrange
        $this->game->hand[$this->game->player] = ["Q" => 1, "B" => 0, "S" => 2, "A" => 0, "G" => 3];

        // Act
        $result = $this->controller->getPieces($this->game);

        // Assert
        $this->assertCount(3, $result);
        $this->assertContains('<option value="Q">Q</option>', $result);
        $this->assertContains('<option value="S">S</option>', $result);
        $this->assertContains('<option value="G">G</option>', $result);
        $this->assertNotContains('<option value="B">B</option>', $result);
        $this->assertNotContains('<option value="A">A</option>', $result);
    }

    public function testGetPiecesReturnsAllOptionsWhenPlayerHasAllTiles()
    {
        // Arrange
        $this->game->hand[$this->game->player] = ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3];

        // Act
        $result = $this->controller->getPieces($this->game);

        // Assert
        $this->assertCount(5, $result);
        $this->assertContains('<option value="Q">Q</option>', $result);
        $this->assertContains('<option value="B">B</option>', $result);
        $this->assertContains('<option value="S">S</option>', $result);
        $this->assertContains('<option value="A">A</option>', $result);
        $this->assertContains('<option value="G">G</option>', $result);
    }
}