<?php

namespace Hangman;

/**
 * Hangman.
 */
class Hangman
{
    private $gamesStore;
    private $wordsStore;
    
    /**
     * Constructor for Hangman.
     * @param GamesStore $gamesStore
     * @param WordsStore $wordsStore
     */
    public function __construct(GamesStore $gamesStore, WordsStore $wordsStore)
    {
        $this->gamesStore = $gamesStore;
        $this->wordsStore = $wordsStore;
    }
    
    /**
     * Starts a Hangman Game.
     * @returns Game
     */
    public function startGame()
    {
        $game = new Game($this->gamesStore);
        return $game->create($this->wordsStore->getRandomWord());
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
