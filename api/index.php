<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim();
$conf = (object) [
    "databaseFilepath" => __DIR__ . "/games.sdb",
    "wordsFilepath" => __DIR__ . "/words.json",
];
$pdo = new \PDO("sqlite:{$conf->databaseFilepath}");
$hangman = new \Hangman\Hangman($pdo, $conf->wordsFilepath);

/**
 * Start a new game.
 */
$app->post('/games', function () use ($hangman) {
    return $hangman->startGame();
});

/**
 * Overview of all games.
 */
$app->get('/games', function () use ($hangman) {
    return $hangman->listGames();
});

/**
 * Get a specific game.
 */
$app->get('/games/:id', function ($id) use ($hangman) {
    return $hangman->getGame($id);
});

/**
 * Guessing a letter.
 */
$app->post('/games/:id', function ($id) use ($app, $hangman) {
    parse_str($app->request->getBody(), $params);
    if (empty($params["char"])) {
        throw new Exception("precondition failed, no char");
    }
    $char = $params["char"];
    if (!is_string($char) || !preg_match("/^[a-z]$/", $char)) {
        throw new Exception("illegal input value for char");
    }
    $hangman->guess($id, $char);
});

$app->run();
