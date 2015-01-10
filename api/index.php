<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim();
$conf = (object) [
    "databaseFilepath" => __DIR__ . "/games.sdb",
    "wordsFilepath" => __DIR__ . "/words.json",
];
$pdo = new \PDO("sqlite:{$conf->databaseFilepath}");

// Start a new game
$app->post('/games', function () use ($pdo, $conf) {
    $game = new \Hangman\Game($pdo);
    if (!is_file($conf->wordsFilepath)) {
        throw new \Exception("words file cannot be found"); //500
    }
    $wordsJson = file_get_contents($conf->wordsFilepath);
    $words = json_decode($conf->wordsFilepath);
    if (empty($words) || empty($words->words)) {
        throw new \Exception("no words"); //500
    }
    $word = $words->words[mt_rand() % count($words->words)];
    $game->create($word);
    return $game;
});

// Overview of all games
$app->get('/games', function () use ($pdo) {
    $games = new \Hangman\Games($pdo);
    $games->load();
    return $games;
});

// Get a specific game
$app->get('/games/:id', function ($id) use ($pdo) {
    $game = new \Hangman\Game($pdo);
    $game->load($id);
    return $game;
});

// Guessing a letter
$app->post('/games/:id', function ($id) use ($pdo, $app) {
    $game = new \Hangman\Game($pdo);
    $game->load($id);
    if ($game->getStatus() === \Hangman\Game::STATUS_BUSY) {
        throw new Exception(); // Precondition failed, game closed
    }
    $body = $app->request->getBody();
    parse_str($body, $params);
    if (empty($params["char"])) {
        throw new Exception(); // Precondition failed, no char
    }
    $char = $params["char"];
    if (!is_string($char) || !preg_match("/^[a-z]$/", $char)) {
        throw new Exception(); // Illegal input for char
    }
    $result = $game->guess($char);
    return [
        "result" => $result,
        "game" => $game
    ];
});

$app->run();
