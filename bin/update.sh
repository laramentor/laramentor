#!/bin/bash
# This script updates the application.

composer install
php artisan migrate
npm install
npm run dev
