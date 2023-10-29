# README

```
$ git clone https://github.com/osipovrb/quiz-api.git
$ cd quiz-api
$ cp .env.example .env
$ docker run --rm --interactive --tty --volume $PWD:/app composer install
$ docker run --rm --interactive --tty --volume $PWD:/app php:8.2-fpm-alpine php /app/artisan key:generate
$ docker compose up --build 
```