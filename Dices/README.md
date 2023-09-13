# Laravel API-REST
## Description
Dice Game application API REST developed using **Laravel** framework to serve any frontend.[**(Open Production API)**](https://rolling-dices-api.fly.dev)
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

- **CORS**: CORS issue has been avoided by adding a prefix (api/v1) to all the routes.
 
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