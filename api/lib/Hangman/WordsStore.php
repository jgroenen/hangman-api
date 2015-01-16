<?php

namespace Hangman;

/**
 * Words Storage Provider for Game of Hangman.
 */
class WordsStore
{
    private $wordsFilepath;
    
    /**
     * Setter for words filepath.
     * @param string $wordsFilepath
     */
    public function setWordsFilepath($wordsFilepath)
    {
        $this->wordsFilepath = $wordsFilepath;
    }
    
    /**
     * Gets a random word.
     * @returns string
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