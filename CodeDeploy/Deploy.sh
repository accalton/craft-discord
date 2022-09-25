#!/bin/bash
cd /var/www/html
composer install
php craft project-config/apply