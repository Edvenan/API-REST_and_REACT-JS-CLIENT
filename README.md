# ROLLING DICES!

# Laravel API-REST
## Description
Dice Game application API REST developed using **Laravel** framework to serve any frontend.(Open Production API)[https://rolling-dices-api.fly.dev]

- Two different user roles implemented:
  - Player:
  - Admin:

- MySQL database implemented.
    (To create the database and its tables run: ```php artisan migrate --force```)

- Authorization process using **Laravel Passport**.
  (To generate Personal Access Keys: ```> php artisan passport:keys```)

- **Seeders and Factories** implemented to create:
  - 1 Admin user (email:'admin@admin.com' password:'password')
  - 10 Players with 10 games each (password:'password')
    (To run them: ```> php artisan db:seed```)

- Feature tests implemented usoing **PHPUnit** for Player, Admin and Authorization (login, register and logout) actions.
    (To run them all: ```> php artisan test --testsuite=Feature --stop-on-failure```)

- Continuous Deployment implemented between Hosting provider (`Fly.io`) and GitHub: everytime a new code version is pushed to GitHub, a GitHub Action will be triggered to deploy the API automatically.


# REACT JS Client
Dice game SPA client developed using REACT JS libraries to interact with above API REST.(Open Production Client)[https://rolling-dices-cleint.netlify.app]

- Login, Registration and Logout features implemented.
- Three route paths used:
  - "/" : Home page
  - "/player" : Player page (once logged in)
  - "/admin" : Admin page (once logged in)
  Any other route will be redirected to either of the above paths, depending if the user is logged in and his role.

- This client app allows for all the functionality explicitly detailed in the requirements list. Those missing details have been interpreted by the developer.

- User input data validation is carried out by the API, not by the client, so as to test the API implementation.
  
- Continuous Deployment implemented between Hosting provider (`Netlify.com`) and GitHub: everytime a new code version of the client is pushed to GitHub, a GitHub Action will be triggered to deploy the API automatically.