#!/bin/bash
./phpunit.phar --bootstrap ../vendor/autoload.php GameTest.php
./phpunit.phar --bootstrap ../vendor/autoload.php GamesTest.php
./phpunit.phar --bootstrap ../vendor/autoload.php HangmanTest.php