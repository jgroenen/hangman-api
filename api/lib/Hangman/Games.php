<?php

namespace Hangman;

/**
 * Games of Hangman.
 */
class Games implements \jsonSerializable
{
    private $pdo;
    private $games;
    
    /**
     * Constructor for Games of Hangman.
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->games = [];
    }
    
    /**
     * Loads all games.
     */
    public function load()
    {
        $sql = "SELECT * FROM games";
        $stmt = $this->pdo->query($sql);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->games = [];
        foreach($results as $result) {
            $this->games[] = new Game($this->pdo, $result);
        }
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
