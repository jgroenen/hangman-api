<?php

namespace Hangman;

/**
 * Games of Hangman.
 */
class Games implements \jsonserializable
{
    private $pdo;
    private $games;
    
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->games = [];
    }
    
    /**
     * 
     */
    public function load()
    {
        $sql = "SELECT * FROM games";
        $this->pdo->query(\PDO::FETCH_ASSOC);
        $results = $this->pdo->fetchAll(\PDO::FETCH_ASSOC);
        $this->games = [];
        foreach($results as $result) {
            $this->games[] = new Game($pdo, $result);
        }
    }
    
    /**
     * 
     */
    public function jsonSerialize()
    {
        return [
            "games" => $this->games;
        ];
    }
}