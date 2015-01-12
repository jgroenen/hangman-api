<?php

namespace Hangman;

/**
 * Hangman.
 */
class Hangman
{
    private $pdo;
    private $wordsFilepath;
    
    /**
     * Constructor for Hangman.
     */
    public function __construct(\PDO $pdo, $wordsFilepath)
    {
        $this->pdo = $pdo;
        $this->wordsFilepath = $wordsFilepath;
    }
    
    /**
     * Starts a Hangman Game.
     * @throws \Exception
     * @returns Game
     */
    public function startGame()
    {
        $game = new Game($this->pdo);
        if (!is_file($this->wordsFilepath)) {
            throw new \Exception("no words file found");
        }
        $wordsJson = file_get_contents($this->wordsFilepath);
        $words = json_decode($wordsJson);
        if (empty($words) || empty($words->words)) {
            throw new \Exception("empty or illegal words file");
        }
        $word = $words->words[mt_rand() % count($words->words)];
        $game->create($word);
        return $game;
    }
    
    /**
     * Lists all Hangman Games.
     * @returns Games
     */
    public function listGames()
    {
        $games = new Games($this->pdo);
        $games->load();
        return $games;
    }
    
    /**
     * Gets a Hangman Game with $id
     * @param int $id
     * @returns Game
     */
    public function getGame($id)
    {
        $game = new Game($this->pdo);
        $game->load($id);
        return $game;
    }
    
    /**
     * Guesses a character in Hangman Game with $id.
     * @param int $id
     * @param string $char
     * @throws \Exception
     * @returns Array
     */
    public function guess($id, $char)
    {
        $game = new Game($this->pdo);
        $game->load($id);
        if ($game->getStatus() !== Game::STATUS_BUSY) {
            return [
                "result" => Game::RESULT_GAME_CLOSED,
                "game" => $game
            ];
        }
        $result = $game->guess($char);
        return [
            "result" => $result,
            "game" => $game
        ];
    }
}
