#!/bin/bash

cd /app

composer install --no-interaction --prefer-dist

php src/main.php
