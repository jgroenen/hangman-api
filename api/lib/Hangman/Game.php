<?php

namespace Hangman;

/**
 * Game of Hangman.
 */
class Game implements \jsonSerializable
{
    const STATUS_BUSY = "busy";
    const STATUS_FAIL = "fail";
    const STATUS_SUCCESS = "success";
    
    const RESULT_GAME_CLOSED = "game closed";
    const RESULT_ILLEGAL_CHAR = "illegal char";
    const RESULT_FAIL = "fail";
    const RESULT_SUCCESS = "success";
    
    private $pdo;
    
    private $attributes = ["id", "word", "guessed", "triesLeft", "status"];
    private $id;
    private $word;
    private $guessed;
    private $triesLeft;
    private $status;
    
    /**
     * Constructs a game.
     * @param PDO $pdo
     * @param Array $game [optional]
     */
    public function __construct(\PDO $pdo, Array $gameValues = [])
    {
        $this->pdo = $pdo;
        $this->loadGameFromArray($gameValues);
    }
    
    /**
     * Loads game attributes from an array of values.
     */
    private function loadGameFromArray(Array $values)
    {
        foreach ($this->attributes as $attribute) {
            if (isset($values[$attribute])) {
                $this->{$attribute} = $values[$attribute];
            }
        }
        return $this;
    }
    
    /**
     * Loads database information into game.
     * @param int $id
     * @returns Game
     */
    public function load($id)
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
        $gameValues = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $gameValues ? $this->loadGameFromArray($gameValues) : null;
    }
    
    /**
     * Create a new game for the given word.
     * @param string $word
     */
    public function create($word)
    {
        $this->word = $word;
        $this->guessed = str_repeat(".", strlen($word));
        $this->triesLeft = 10;
        $this->status = self::STATUS_BUSY;
        return $this->save();
    }
    
    /**
     * Guesses a character in the word.
     * @param string $char
     * @returns RESULT_SUCCESS || RESULT_FAIL
     */
    public function guess($char)
    {
        if ($this->status !== self::STATUS_BUSY) {
            return [
                "result" => self::RESULT_GAME_CLOSED,
                "game" => $this
            ];
        }
        if (!is_string($char) || !preg_match("/^[a-z]$/", $char)) {
            return [
                "result" => self::RESULT_ILLEGAL_CHAR,
                "game" => $this
            ];
        }
        $L = strlen($this->word);
        $result = self::RESULT_FAIL;
        $dots = 0;
        for ($i = 0; $i < $L; ++$i) {
            if ($this->word[$i] === $char && $this->guessed[$i] !== $char) {
                $this->guessed[$i] = $char;
                $result = self::RESULT_SUCCESS;
            }
            if ($this->guessed[$i] === ".") {
                $dots++;
            }
        }
        switch ($result) {
            case self::RESULT_FAIL:
                $this->triesLeft--;
                if ($this->triesLeft <= 0) {
                    $this->status = self::STATUS_FAIL;
                }
                break;
            case self::RESULT_SUCCESS:
                if (!$dots) {
                    $this->status = self::STATUS_SUCCESS;
                }
                break;
        }
        return [
            "result" => $result,
            "game" => $this->save()
        ];
    }
    
    /**
     * Saves game information into database.
     */
    private function save()
    {
        if (empty($this->id)) {
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
                ":word" => $this->word,
                ":guessed" => $this->guessed,
                ":triesLeft" => $this->triesLeft,
                ":status" => $this->status
            ]);
            $this->id = $this->pdo->lastInsertId();
        } else {
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
                ":word" => $this->word,
                ":guessed" => $this->guessed,
                ":triesLeft" => $this->triesLeft,
                ":status" => $this->status,
                ":id" => $this->id
            ]);
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
            "id" => $this->id,
            "word" => $this->guessed,
            "triesLeft" => $this->triesLeft,
            "status" => $this->status
        ];
    }
}
