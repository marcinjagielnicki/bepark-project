
## Bepark test project

This is a test project made for BePark job offer. Project contains Laravel framework, docker-compose configuration, TailwindCSS as CSS framework.

## Architecture & business case

Code is build on top of repositories. Generic game repository (GameRepository) is decorated by Database and Cache decorators. This approach allows for easy change of flow of data (e.g. very fast way for autocomplete mechanism with fallback to 3rd party APIs).

GameDictionaries are responsible for fetching and transforming data from external data sources into one unified DTO.

### Business case:
1. User is able to create account
2. User is able to login
3. User is able to see his game library
4. User is able to add ANY game into library
5. User wants to see additional data (rating, cover, description, platforms) for his games.


### Technical solution

Games are stored inside games datatable. User owns games by Many-To-Many relationship. 

When user is adding a game, application is checking in game exists in games table (Using Cache(Database) decorators on GameRepository). If game doesn't exist, application is dispatching FetchGameDataJob for a stub entity, in order to fetch data from external services (in syn/async way - depending on configuration).

At this moment application supports 4 data sources:
1. Cache
2. Database
3. Rawg.io
4. Twitch API

 

## Installation

### Requirements

1. Linux/Mac OS
2. Docker
3. Docker-compose

### Steps to install

1. Clone repository
2. run in console `make warmup-project`
3. Open `http://localhost:8088` in your browser
4. Create account and login
