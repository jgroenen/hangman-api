<?php

namespace Hangman;

/**
 * Words Storage Provider for Game of Hangman.
 */
class WordsStore
{
    private $wordsFilepath;
    
    /**
     * Constructs a Games Store for Hangman Games.
     * @param string $wordsFilepath
     */
    public function __construct($wordsFilepath)
    {
        $this->wordsFilepath = $wordsFilepath;
    }
    
    /**
     * Gets a random word.
     */
    public function getRandomWord()
    {
        if (!is_file($this->wordsFilepath)) {
            throw new \Exception("no words file found");
        }
        $wordsJson = file_get_contents($this->wordsFilepath);
        $words = json_decode($wordsJson);
        if (empty($words) || empty($words->words)) {
            throw new \Exception("empty or illegal words file");
        }
        return $words->words[mt_rand() % count($words->words)];
    }
}