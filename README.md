# ttapp

## Requirements
* Docker
* node.js

## Installation
#### 1. docker 
Following command initializes empty database, php-fpm + nginx
```
$ docker-compose up -d
```
Database migrations are handled from inside php-fpm container. To see list of available commands connect to docker container.
```
$ docker-compose exec php-fpm bash
```
To run migrations after initializing (from /var/www path inside container), run
```
bin/console doctrine:migrations:migrate
```
All database tables should be created during that process.

#### 2. Frontend
Navigate to frontend directory:
```
$ cd frontend
```
run
```
$ npm install
```
To run dev environment:
```
$ npm start dev
```

##### How to deploy frontend to production
1. `cd frontend`
2. `npm install`
3. `npm run build`

that's it, nginx uses the folder frontend/dist as mounted volume, so to update a build
is enough to just rerun the build command
