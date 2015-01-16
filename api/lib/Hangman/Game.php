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
    
    private $gamesStore;
    
    private $attributes = ["id", "word", "guessed", "triesLeft", "status"];
    
    private $id;
    private $word;
    private $guessed;
    private $triesLeft;
    private $status;
    
    /**
     * Constructs a Game.
     * @param GamesStore $gamesStore
     * @param Array $gameValues [optional]
     */
    public function __construct(GamesStore $gamesStore, Array $gameValues = [])
    {
        $this->gamesStore = $gamesStore;
        $this->loadGameFromArray($gameValues);
    }
    
    /**
     * Setter for id.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * Getter for id.
     * @returns int id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Getter for word.
     * @returns string word
     */
    public function getWord()
    {
        return $this->word;
    }
    
    /**
     * Getter for guessed.
     * @returns string guessed
     */
    public function getGuessed()
    {
        return $this->guessed;
    }
    
    /**
     * Getter for triesLeft.
     * @returns int triesLeft
     */
    public function getTriesLeft()
    {
        return $this->triesLeft;
    }
    
    /**
     * Getter for status.
     * @returns string status
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Loads Game attributes from an Array of values.
     * @param Array $values
     * @returns Game $this
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
     * Loads values from Game with $id from Games Store.
     * @param int $id
     * @returns Game $this or null
     */
    public function load($id)
    {
        $gameValues = $this->gamesStore->fetch($id);
        return $gameValues ? $this->loadGameFromArray($gameValues) : null;
    }
    
    /**
     * Creates new Game with word $word.
     * @param string $word
     * @returns Game $this
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
     * Guesses character $char in word.
     * @param string $char
     * @returns GuessResult
     */
    public function guess($char)
    {
        if ($this->status !== self::STATUS_BUSY) {
            return new GuessResult(GuessResult::RESULT_GAME_CLOSED, $this);
        }
        if (!is_string($char) || !preg_match("/^[a-z]$/", $char)) {
            return new GuessResult(GuessResult::RESULT_ILLEGAL_CHAR, $this);
        }
        $L = strlen($this->word);
        $resultCode = GuessResult::RESULT_FAIL;
        $dots = 0;
        for ($i = 0; $i < $L; ++$i) {
            if ($this->word[$i] === $char && $this->guessed[$i] !== $char) {
                $this->guessed[$i] = $char;
                $resultCode = GuessResult::RESULT_SUCCESS;
            }
            if ($this->guessed[$i] === ".") {
                $dots++;
            }
        }
        switch ($resultCode) {
            case GuessResult::RESULT_FAIL:
                $this->triesLeft--;
                if ($this->triesLeft <= 0) {
                    $this->status = self::STATUS_FAIL;
                }
                break;
            case GuessResult::RESULT_SUCCESS:
                if (!$dots) {
                    $this->status = self::STATUS_SUCCESS;
                }
                break;
        }
        return new GuessResult($resultCode, $this->save());
    }
    
    /**
     * Saves Game values into Games Store.
     */
    private function save()
    {
        $this->gamesStore->save($this);
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
