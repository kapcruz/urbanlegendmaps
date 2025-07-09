## Installation:

Clone the repository and start the containers using Docker Compose:


```bash
git clone git@github.com:kapcruz/urbanlegendmaps.git urbanlegend

cd urbanlegend/backend

cp .env.example .env

```

## Install the dependencies:

```bash

docker exec -it urbanlegend_app composer install

docker exec -it urbanlegend_app php artisan key:generate

docker exec -it urbanlegend_app php artisan migrate
```

## Accessing phpMyAdmin

Url: http://localhost:8080

server: urbanlegend_mysql \
user: root \
senha: root
