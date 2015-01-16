<?php

class HangmanTest extends PHPUnit_Framework_TestCase
{
    public function testConstructHangman()
    {
        $hangman = new \Hangman\Hangman;
    }
    
    public function testSetGamesStore()
    {
        $gamesStore = $this
            ->getMockBuilder('Hangman\GamesStore')
            ->getMock();
        $hangman = new \Hangman\Hangman;
        $hangman->setGamesStore($gamesStore);
    }
    
    public function testSetWordsStore()
    {
        $wordsStore = $this
            ->getMockBuilder('Hangman\WordsStore')
            ->getMock();
        $hangman = new \Hangman\Hangman;
        $hangman->setWordsStore($wordsStore);
    }
}
