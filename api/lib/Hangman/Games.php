<?php

namespace Hangman;

/**
 * Games of Hangman.
 */
class Games implements \jsonSerializable
{
    private $gamesStore;
    private $games;
    
    /**
     * Constructor for Games of Hangman.
     */
    public function __construct(GamesStore $gamesStore)
    {
        $this->gamesStore = $gamesStore;
        $this->games = [];
    }
    
    /**
     * Loads all games.
     */
    public function load()
    {
        $results = $this->gamesStore->fetch();
        $this->games = [];
        foreach($results as $result) {
            $this->games[] = new Game($this->gamesStore, $result);
        }
        return $this;
    }
    
    /**
     * See jsonSerializable interface specification.
     * @returns Array
     */
    public function jsonSerialize()
    {
        return [
            "games" => $this->games,
        ];
    }
}
