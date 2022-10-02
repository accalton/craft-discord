#!/bin/bash
cd /var/www/html
composer install
npm install
php craft project-config/apply
npm run build
chown -R apache:apache ./