# REACT JS Client
## Description
Dice game SPA client developed using REACT JS libraries to interact with above API REST.[**(Open Production Client)**](https://rolling-dices-cleint.netlify.app)

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

## Captions
![Client's Home Page](https://github.com/Edvenan/API-REST/assets/97369106/cc9124d8-607a-4d61-8571-8114815aaf2b)
![Client's Player Page](https://github.com/Edvenan/API-REST/assets/97369106/9eab754d-fcf9-486b-8fbe-7d48f2d4e8be)
![Client's Admin Page](https://github.com/Edvenan/API-REST/assets/97369106/668a393d-a845-454f-a2bf-528dffdd96e4)
