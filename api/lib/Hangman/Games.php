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
     * Constructor for Games.
     * @param GamesStore $gamesStore
     */
    public function __construct(GamesStore $gamesStore)
    {
        $this->gamesStore = $gamesStore;
        $this->games = [];
    }
    
    /**
     * Loads all Games.
     */
    public function load()
    {
        $this->games = [];
        foreach($this->gamesStore->fetch() as $gameValues) {
            $this->games[] = new Game($this->gamesStore, $gameValues);
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
