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
     * Setter for Games Store.
     * @param GamesStore $gamesStore
     */
    public function setGamesStore(GamesStore $gamesStore)
    {
        $this->gamesStore = $gamesStore;
    }
    
    /**
     * Setter for Words Store.
     * @param WordsStore $wordsStore
     */
    public function setWordsStore(WordsStore $wordsStore)
    {
        $this->wordsStore = $wordsStore;
    }
    
    /**
     * Starts a Game.
     * @returns Game
     */
    public function startGame()
    {
        $game = new Game($this->gamesStore);
        return $game->create($this->wordsStore->getRandomWord());
    }
    
    /**
     * Lists all Games.
     * @returns Games
     */
    public function listGames()
    {
        $games = new Games($this->gamesStore);
        return $games->load();
    }
    
    /**
     * Gets a Game with $id
     * @param int $id
     * @returns Game
     */
    public function getGame($id)
    {
        $game = new Game($this->gamesStore);
        return $game->load($id);
    }
}
