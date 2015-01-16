<?php

namespace Hangman;

/**
 * Hangman.
 */
class Hangman
{
    private $gamesStore;
    private $wordsFilepath;
    
    /**
     * Constructor for Hangman.
     */
    public function __construct(GamesStore $gamesStore, $wordsFilepath)
    {
        $this->gamesStore = $gamesStore;
        $this->wordsFilepath = $wordsFilepath;
    }
    
    /**
     * Gets a random word from the wordfile.
     * @throws \Exception
     * @returns string
     */
    private function getRandomWord()
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
    
    /**
     * Starts a Hangman Game.
     * @returns Game
     */
    public function startGame()
    {
        $game = new Game($this->gamesStore);
        return $game->create($this->getRandomWord());
    }
    
    /**
     * Lists all Hangman Games.
     * @returns Games
     */
    public function listGames()
    {
        $games = new Games($this->gamesStore);
        return $games->load();
    }
    
    /**
     * Gets a Hangman Game with $id
     * @param int $id
     * @returns Game
     */
    public function getGame($id)
    {
        $game = new Game($this->gamesStore);
        return $game->load($id);
    }
}
