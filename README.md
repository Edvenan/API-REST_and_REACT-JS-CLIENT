# ROLLING DICES!
## Introduction

Dice game app in which the player rolls two dices in each round. If the sum of both dices is 7, the player wins the round. He loses otherwise. 
The winner is the player with the highest rate of wins.\
Development of the game is divided into a backend API and a frontend client, both deployed and hosted in separate hosting providers. 

<p align='center'>
<img src="https://github.com/Edvenan/API-REST/assets/97369106/93300b7c-69bc-4110-898a-45b2ab6404ea" alt="Rolling Dices home page"  width="50%">
</p>

Two roles have been defined for this app:
- **Player** (they can be created via registration/sign-up process)
- **Admin** (pre-defined in the database)

## Game Mechanics: ##
A Player has the following options:
  - See his score (rate of wins in %)
  - Play a game (roll the dices)
  - See his games list
  - Delete all his games at once (not a particular one)
  - Edit his user name

<p align='center'>
<img src="https://github.com/Edvenan/API-REST/assets/97369106/e9b1c4cb-d28c-47dc-8b7a-fe799bc35ec9" alt="Rolling Dices Player page" width="50%">
</p>

An Admin has the following options:

  - See all players average score (average rate of winds in %)
  - See the ranking
  - See the winner(s)
  - See the loser(s)
  - See list of all players
  - See the games list of a particular player
  - Edit his user name
  - Edit a player's username
  - Delete a player's games
  - Delete a player (and his games)
  - Refresh the data being shown on screen

<p align='center'>
<img src="https://github.com/Edvenan/API-REST/assets/97369106/eaf0937f-8902-453f-b76b-578efa174560" alt="Rolling Dices Admin page" width="50%">
</p>

# Laravel PHP API-REST
## Description
Dice Game application API REST developed using **Laravel PHP** framework to serve any frontend.[**(Open Production API Welcome page)**](https://rolling-dices-api.fly.dev)
The game consists of rolling two dices in each round. If the sum of both dices equals 7, the player wins the round. He loses otherwise.

- **Routes** implemented:
  
      POST /players : create a player
      PUT /players/{id} : edit player's name
      POST /players/{id}/games/ : a particular player rolls the dices
      DELETE /players/{id}/games: delete a player's games
      GET /players: returns the list of players and its average wins rate 
      GET /players/{id}/games: returns the list of games of a particular player
      GET /players/ranking: returns the ranking
      GET /players/ranking/loser: returns the player with lowest wins rate
      GET /players/ranking/winner: returns the player with highest wins rate
      DELETE /players/{id}: delete a player

- **CORS**: CORS issue has been avoided by adding a prefix (```api/v1```) to all the routes.
Use Control + Shift + m to toggle the tab key moving focus. Alternatively, use esc then tab to move to the next interactive element on the page.
No file chosen
Attach files by dragging & dropping, selecting or pasting them.
Editing API-REST/README.md at develop · Edvenan/API-REST
