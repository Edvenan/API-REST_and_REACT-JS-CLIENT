# ROLLING DICES!
## Introduction

Dice game app consisting of rolling two dices in each round. If the sum of both dices equals 7, the player wins the round. He loses otherwise.

![Client Home Page](https://github.com/Edvenan/API-REST/assets/97369106/93300b7c-69bc-4110-898a-45b2ab6404ea)

Two roles have been defined for this app:
- Player (they can be created via registration/sign-up process)
- Admin (pre-defined in the database)

## Game Mechanics: ##
A Player has the following options:
  - See his score (rate of wins in %)
  - Play a game (roll the dices)
  - See his games list
  - Delete all his games at once (not a particular one)
  - Edit his user name
    
![Client Player Page](https://github.com/Edvenan/API-REST/assets/97369106/e9b1c4cb-d28c-47dc-8b7a-fe799bc35ec9)

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
    
<img src="[https://your-image-url.type](https://github.com/Edvenan/API-REST/assets/97369106/eaf0937f-8902-453f-b76b-578efa174560)" height="50%">


# Laravel PHP API REST
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
 
- **MySQL** database implemented.\
    (To create the database and its tables run: ```php artisan migrate --force```)

- **Authentication** process using **Laravel Passport**.\
  (To generate Personal Access Keys: ```> php artisan passport:keys```)

- **Roles**: two different user roles implemented, Player and Admin, to allow the assignment of different options (as per the 'Game Mechanics' section).
  
- **Seeders and Factories** implemented to create:
  - 1 Admin user (```email:'admin@admin.com' password:'password'```)
  - 10 Players with 10 games each (```password:'password'```)\
    (To run them: ```> php artisan db:seed```)

- **Feature test suites** for Player, Admin and Authorization (login, register and logout) actions implemented using **PHPUnit**.\
    (To run them all: ```> php artisan test --testsuite=Feature --stop-on-failure```)

- **Continuous Deployment** implemented between Hosting provider (`Fly.io`) and GitHub: everytime a new code version is pushed to GitHub, a GitHub Action will be triggered to deploy the API automatically.


# REACT JS Client
## Description

Dice game SPA client developed using REACT JS libraries to interact with above API REST.[**(Open Production Client)**](https://rolling-dices-client.netlify.app)

The client app allows for all the functionality explicitly detailed in the requirements list. Those missing details have been interpreted by the developer.

- **Authentication**: Login, Registration and Logout features implemented but relying on the API for authentication.
  
- **Routes**: three route paths used:
  
      "/" : Home page
      "/player" : Player page (once logged in)
      "/admin" : Admin page (once logged in)

  Any other route will show a **404 Not Found page** and will then redirect to either of the above paths, depending on whether the user is logged or not and his role.

- Use of browser's **sessionStorage** to keep session alive during page reload.
  
- User input **data validation** is carried out by the API, not by the client, so as to test the API implementation.

- **Sound effects** added to the game when user plays a game.

- **Continuous Deployment** implemented between Hosting provider (`Netlify.com`) and GitHub: everytime a new code version of the client is pushed to GitHub, a GitHub Action will be triggered to deploy the API automatically.
