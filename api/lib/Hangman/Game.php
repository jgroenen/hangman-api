<?php

namespace Hangman;

/**
 * Game of Hangman.
 */
class Game implements \jsonserializable
{
    const STATUS_BUSY = "busy";
    const STATUS_FAIL = "fail";
    const STATUS_SUCCESS = "success";
    
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
            "id" => $id
        ]);
        $gameValues = $this->pdo->fetch(\PDO::FETCH_ASSOC);
        $this->loadGameFromArray($gameValues);
    }
    
    /**
     * Create a new game for the given word.
     * @param string $word
     */
    public function create($word)
    {
        $this->word = $word;
        $this->guessed = str_repeat(".", count($word));
        $this->triesLeft = 10;
        $this->status = self::STATUS_BUSY;
        
        $this->save();
    }
    
    /**
     * Guesses a character in the word.
     * @param char $char
     * @returns RESULT_SUCCESS || RESULT_FAIL
     */
    public function guess($char)
    {
        $L = count($this->word);
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
        $this->save();
        return $result;
    }
    
    /**
     * Saves game information into database.
     */
    private function save()
    {
        if (empty($this->attrs["id"])) {
            $sql = "
                INSERT INTO games
                SET
                    word = :word,
                    guessed = :guessed,
                    tries_left = :triesLeft,
                    status = :status
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                "word" => $this->word,
                "guessed" => $this->guessed,
                "triesLeft" => $this->triesLeft,
                "status" => $this->status
            ]);
            $this->id = $this->pdo->lastInsertId();
        } else {
            $sql = "
                UPDATE games
                SET
                    word = :word,
                    guessed = :guessed,
                    tries_left = :triesLeft,
                    status = :status
                WHERE
                    id = :id
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                "word" => $this->word,
                "guessed" => $this->guessed,
                "triesLeft" => $this->triesLeft,
                "status" => $this->status,
                "id" => $this->id
            ]);
        }
    }
    
    /**
     * Returns the fields that should show up in the json.
     */
    public function jsonSerialize()
    {
        return [
            "id" => $this->attrs["id"],
            "word" => $this->attrs["guessed"],
            "tries_left" => $this->attrs["triesLeft"],
            "status" => $this->attrs["status"]
        ];
    }
}