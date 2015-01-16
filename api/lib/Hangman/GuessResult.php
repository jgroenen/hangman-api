<?php

namespace Hangman;

/**
 * Guess Result for Game of Hangman.
 */
class GuessResult implements \jsonSerializable
{
    const RESULT_GAME_CLOSED = "game closed";
    const RESULT_ILLEGAL_CHAR = "illegal char";
    const RESULT_FAIL = "fail";
    const RESULT_SUCCESS = "success";
    
    private $result;
    private $game;
    
    /**
     * Constructor for Guess Result.
     * @param string $result
     * @param Game $game
     */
    public function __construct($result, Game $game)
    {
        $this->result = $result;
        $this->game = $game;
    }
    
    /**
     * See jsonSerializable interface specification.
     * @returns Array asd
     */
    public function jsonSerialize()
    {
        return [
            "result" => $this->result,
            "game" => $this->game
        ];
    }
}