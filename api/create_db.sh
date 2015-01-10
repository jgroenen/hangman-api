#!/bin/bash
sqlite3 games.sdb "CREATE TABLE IF NOT EXISTS games (
    id INTEGER PRIMARY KEY,
    word TEXT,
    guessed TEXT,
    tries_left INTEGER,
    status TEXT
)"