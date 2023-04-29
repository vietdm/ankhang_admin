#!/bin/bash

docker-compose up --build -d
docker-compose exec ak_admin_app sh -c "composer create-project laravel/laravel apps"
mv ./apps/* .
mv ./apps/.* .
rm -r apps
