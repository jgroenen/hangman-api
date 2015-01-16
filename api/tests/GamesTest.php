<?php

class GamesTest extends PHPUnit_Framework_TestCase
{
    public function testConstructGames()
    {
        $gamesStore = $this
            ->getMockBuilder('Hangman\GamesStore')
            ->getMock();
        $games = new \Hangman\Games($gamesStore);
    }
    
    public function testJsonSerialize()
    {
        $gamesStore = $this
            ->getMockBuilder('Hangman\GamesStore')
            ->getMock();
        $games = new \Hangman\Games($gamesStore);
        $json = $games->jsonSerialize();
        $this->assertEquals(true, is_array($json));
        $this->assertEquals(true, is_array($json["games"]));
    }
}
