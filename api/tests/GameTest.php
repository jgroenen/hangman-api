<?php

class GameTest extends PHPUnit_Framework_TestCase
{
    public function testConstructGame()
    {
        $gamesStore = $this
            ->getMockBuilder('Hangman\GamesStore')
            ->getMock();
        $game = new \Hangman\Game($gamesStore);
    }
    
    public function testConstructGameWithValuesAndGetters()
    {
        $gamesStore = $this
            ->getMockBuilder('Hangman\GamesStore')
            ->getMock();
        $game = new \Hangman\Game($gamesStore, [
            "id" => 42,
            "word" => "testword",
            "guessed" => "te.tword",
            "triesLeft" => 5,
            "status" => \Hangman\Game::STATUS_BUSY
        ]);
        $this->assertEquals($game->getWord(), "testword");
        $this->assertEquals($game->getGuessed(), "te.tword");
        $this->assertEquals($game->getTriesLeft(), 5);
        $this->assertEquals($game->getStatus(), \Hangman\Game::STATUS_BUSY);
    }
    
    public function testSetAndGetId()
    {
        $gamesStore = $this
            ->getMockBuilder('Hangman\GamesStore')
            ->getMock();
        $game = new \Hangman\Game($gamesStore);
        $game->setId(42);
        $this->assertEquals($game->getId(), 42);
    }
    
    public function testCreate()
    {
        $gamesStore = $this
            ->getMockBuilder('Hangman\GamesStore')
            ->getMock();
        $game = new \Hangman\Game($gamesStore);
        $game->create("testword");
        $this->assertEquals($game->getWord(), "testword");
        $this->assertEquals($game->getGuessed(), "........");
        $this->assertEquals($game->getTriesLeft(), 10);
        $this->assertEquals($game->getStatus(), \Hangman\Game::STATUS_BUSY);
    }
    
    public function testCorrectGuess()
    {
        $gamesStore = $this
            ->getMockBuilder('Hangman\GamesStore')
            ->getMock();
        $game = new \Hangman\Game($gamesStore, [
            "id" => 42,
            "word" => "testword",
            "guessed" => "te.tword",
            "triesLeft" => 5,
            "status" => \Hangman\Game::STATUS_BUSY
        ]);
        $result = $game->guess("s");
        $this->assertEquals($game->getStatus(), \Hangman\Game::STATUS_SUCCESS);
        $this->assertEquals($game->getGuessed(), "testword");
        $this->assertEquals($game->getTriesLeft(), 5);
    }
    
    public function testIncorrectGuess()
    {
        $gamesStore = $this
            ->getMockBuilder('Hangman\GamesStore')
            ->getMock();
        $game = new \Hangman\Game($gamesStore, [
            "id" => 42,
            "word" => "testword",
            "guessed" => "te.tword",
            "triesLeft" => 5,
            "status" => \Hangman\Game::STATUS_BUSY
        ]);
        $result = $game->guess("x");
        $this->assertEquals($game->getStatus(), \Hangman\Game::STATUS_BUSY);
        $this->assertEquals($game->getGuessed(), "te.tword");
        $this->assertEquals($game->getTriesLeft(), 4);
    }
    
    public function testJsonSerialize()
    {
        $gamesStore = $this
            ->getMockBuilder('Hangman\GamesStore')
            ->getMock();
        $game = new \Hangman\Game($gamesStore, [
            "id" => 42,
            "word" => "testword",
            "guessed" => "te.tword",
            "triesLeft" => 5,
            "status" => \Hangman\Game::STATUS_BUSY
        ]);
        $json = $game->jsonSerialize();
        $this->assertEquals(true, is_array($json));
        $this->assertEquals(42, $json["id"]);
        $this->assertEquals("te.tword", $json["word"]);
        $this->assertEquals(5, $json["triesLeft"]);
        $this->assertEquals(\Hangman\Game::STATUS_BUSY, $json["status"]);
    }
}
