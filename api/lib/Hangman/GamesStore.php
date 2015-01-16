<?php

namespace Hangman;

/**
 * Storage Provider for Game of Hangman.
 */
class GamesStore
{
    /**
     * Constructs a Games Store for Hangman Games.
     * @param PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * Fetches Hangman Games.
     * @param int $id
     */
    public function fetch($id = null)
    {
        return $id ? $this->fetchOne($id) : $this->fetchAll();
    }
    
    /**
     * Fetches one Hangman Games.
     * @param int $id
     */
    private function fetchOne($id)
    {
        $sql = "
            SELECT * FROM games
            WHERE id = :id
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":id" => $id
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Fetches all Hangman Games.
     */
    private function fetchAll()
    {
        $sql = "SELECT * FROM games";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Save a Hangman Game.
     * @param Game $game
     */
    public function save(Game $game)
    {
        if ($game->getId()) {
            $this->saveExisting($game);
        } else {
            $this->saveNew($game);
        }
    }
    
    /**
     * Saves a new Game.
     * @param Game $game
     */
    private function saveNew(Game $game)
    {
        $sql = "
            INSERT INTO games (
                word,
                guessed,
                triesLeft,
                status
            )
            VALUES (
                :word,
                :guessed,
                :triesLeft,
                :status
            )
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":word" => $game->getWord(),
            ":guessed" => $game->getGuessed(),
            ":triesLeft" => $game->getTriesLeft(),
            ":status" => $game->getStatus()
        ]);
        $game->setId($this->pdo->lastInsertId());
    }
    
    /**
     * Saves an existing Game.
     * @param Game $game
     */
    private function saveExisting(Game $game)
    {
        $sql = "
            UPDATE games
            SET
                word = :word,
                guessed = :guessed,
                triesLeft = :triesLeft,
                status = :status
            WHERE
                id = :id
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":word" => $game->getWord(),
            ":guessed" => $game->getGuessed(),
            ":triesLeft" => $game->getTriesLeft(),
            ":status" => $game->getStatus(),
            ":id" => $game->getId()
        ]);
    }
}