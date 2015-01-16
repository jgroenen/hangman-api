<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');

require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');
$app->response->headers->set('Access-Control-Allow-Origin', '*');

$conf = (object) [
    "databaseFilepath" => __DIR__ . "/database/games.sdb",
    "wordsFilepath" => __DIR__ . "/words.json",
];

$pdo = new PDO("sqlite:{$conf->databaseFilepath}");
$gamesStore = new \Hangman\GamesStore($pdo);
$wordsStore = new \Hangman\WordsStore($conf->wordsFilepath);
$hangman = new \Hangman\Hangman($gamesStore, $wordsStore);

/**
 * Start a new game.
 */
$app->post('/games', function () use ($hangman) {
    echo json_encode($hangman->startGame());
});

/**
 * Overview of all games.
 */
$app->get('/games', function () use ($hangman) {
    echo json_encode($hangman->listGames());
});

/**
 * Get a specific game.
 */
$app->get('/games/:id', function ($id) use ($app, $hangman) {
    $game = $hangman->getGame($id);
    if (!$game) {
        $app->halt(404, "Game Not Found");
    }
    echo json_encode($game);
});

/**
 * Guessing a letter.
 */
$app->post('/games/:id', function ($id) use ($app, $hangman) {
    $game = $hangman->getGame($id);
    if (!$game) {
        $app->halt(404, "Game Not Found");
    }
    parse_str($app->request->getBody(), $params);
    if (empty($params["char"])) {
        $app->halt(412, "missing parameter char");
    }
    echo json_encode($game->guess($params["char"]));
});

$app->run();
