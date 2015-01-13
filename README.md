# hangman-api
Small web service for playing a game of hangman.

# Implement “hangman”

Method | URL | Request | Response | Description
--- | --- | --- | --- | --- |
<pre>POST</pre> | <pre>/games</pre> | empty | <pre>{<br/>&nbsp;&nbsp;&nbsp;&nbsp;"id": 1,<br/>&nbsp;&nbsp;&nbsp;&nbsp;"word": ".......",<br/>&nbsp;&nbsp;&nbsp;&nbsp;"tries_left": 10,<br/>&nbsp;&nbsp;&nbsp;&nbsp;"status": "busy"<br/>}</pre> | Start a new game
<pre>GET</pre> | <pre>/games</pre> | empty | <pre>{<br/>&nbsp;&nbsp;&nbsp;&nbsp;"games": []<br/>}</pre> | Overview of all games
<pre>GET</pre> | <pre>/games/:id</pre> | empty | <pre>{<br/>&nbsp;&nbsp;&nbsp;&nbsp;"id": 1,<br/>&nbsp;&nbsp;&nbsp;&nbsp;"word": "aw.so..",<br/>&nbsp;&nbsp;&nbsp;&nbsp;"tries_left": 5,<br/>&nbsp;&nbsp;&nbsp;&nbsp;"status": "busy"<br/>}</pre> | Get a specific game
<pre>POST</pre> | <pre>/games/:id</pre> | <pre>char=e</pre> | <pre>{<br/>&nbsp;&nbsp;&nbsp;&nbsp;"result": "success",<br/>&nbsp;&nbsp;&nbsp;&nbsp;"game": {<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"id": 1,<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"word": "awesome",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"tries_left": 3,<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"status": "success"<br/>&nbsp;&nbsp;&nbsp;&nbsp;}<br/>}</pre> | Guessing a letter

* Game field "status" can be "busy" (game in progress), "fail" (game lost) or "success" (game won)
* Guess field "result" can be "fail" (letter not in word), "success" (letter in word) or "game closed"
* Guessing a correct letter doesn’t decrement the amount of tries left
* Only valid characters are a-z
* At the start of the game a random word should be picked from this list

# Running the API

To run, you need to:

* create the sqlite3 database running create_db.sh in /api/database
* get the vendor stuff using composer
* make sure your server uses the .htaccess
 
# Running the app

* just open the index.html in your browser
