#!/bin/bash
sqlite3 games.sdb "CREATE TABLE IF NOT EXISTS games (
    id INTEGER PRIMARY KEY,
    word TEXT,
    guessed TEXT,
    triesLeft INTEGER,
    status TEXT
)"
chmod 777 games.sdb
chmod 777 .
