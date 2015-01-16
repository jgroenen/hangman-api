<?php

namespace Hangman;

/**
 * Games Storage Provider for Game of Hangman.
 */
class GamesStore
{
    private $pdo;
    
    /**
     * Setter for PDO.
     * @param PDO $pdo
     */
    public function setPdo(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * Fetches all Games or Game with $id.
     * @param int $id
     * @returns Array
     */
    public function fetch($id = null)
    {
        return $id ? $this->fetchOne($id) : $this->fetchAll();
    }
    
    /**
     * Fetches one Game.
     * @param int $id
     * @returns Array
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
     * Fetches all Games.
     * @returns Array
     */
    private function fetchAll()
    {
        $sql = "SELECT * FROM games";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Saves Game.
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
     * Saves new Game.
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
     * Saves existing Game.
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