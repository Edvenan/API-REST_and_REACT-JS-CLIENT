# ROLLING DICES!
## Introduction
Dice game app consisting of rolling two dices in each round. If the sum of both dices equals 7, the player wins the round and he loses otherwise.

Two roles have been defined for this app:
- Player (canbe created via registration/sign-up process)
- Admin (pre-defined in the database)

Game Mechanics:
- A Player has the following options:
  - See his score (rate of wins in %)
  - Play a game
  - See his games list
  - Delete all his games at once (not a particular one)
  - Edit his user name

- An Admin has the following options:
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
 

# Laravel API-REST
## Description
Dice Game application API REST developed using **Laravel** framework to serve any frontend.(Open Production API)[https://rolling-dices-api.fly.dev]

- Two different user roles implemented, Player and Admin, to allow the assignment of different features as per the introduction above.

- MySQL database implemented.
    (To create the database and its tables run: ```php artisan migrate --force```)

- Authorization process using **Laravel Passport**.
  (To generate Personal Access Keys: ```> php artisan passport:keys```)

- **Seeders and Factories** implemented to create:
  - 1 Admin user (email:'admin@admin.com' password:'password')
  - 10 Players with 10 games each (password:'password')
    (To run them: ```> php artisan db:seed```)

- Feature test suites for Player, Admin and Authorization (login, register and logout) actions implemented using **PHPUnit**.
    (To run them all: ```> php artisan test --testsuite=Feature --stop-on-failure```)

- **Continuous Deployment** implemented between Hosting provider (`Fly.io`) and GitHub: everytime a new code version is pushed to GitHub, a GitHub Action will be triggered to deploy the API automatically.


# REACT JS Client
## Description
Dice game SPA client developed using REACT JS libraries to interact with above API REST.(Open Production Client)[https://rolling-dices-cleint.netlify.app]

- Login, Registration and Logout features implemented.
  
- Three route paths used:
  - "/" : Home page
  - "/player" : Player page (once logged in)
  - "/admin" : Admin page (once logged in)
  Any other route will be redirected to either of the above paths, depending if the user is logged in and his role.

- Use of browser's **sessionStorage** to keep session alive during page reload.
  
- This client app allows for all the functionality explicitly detailed in the requirements list. Those missing details have been interpreted by the developer.

- User input data validation is carried out by the API, not by the client, so as to test the API implementation.
  
- Continuous Deployment implemented between Hosting provider (`Netlify.com`) and GitHub: everytime a new code version of the client is pushed to GitHub, a GitHub Action will be triggered to deploy the API automatically.