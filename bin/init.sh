#!/bin/bash
# This script sets up the application.

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev
