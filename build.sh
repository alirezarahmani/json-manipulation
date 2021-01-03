#!/usr/bin/env bash
rm .env
if [ ! -f .env ]; then
    touch .env
    echo SERVER_PORT=8080 >> .env
    echo LOCAL_IP=0.0.0.0 >> .env
    echo LOCAL_DEV_DIR=$(pwd) >> .env
fi

docker-compose build
docker-compose up -d
docker-compose exec worker composer install