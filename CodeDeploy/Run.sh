#!/bin/bash
cd /var/www/html
sudo -u apache nohup php craft discordbot/bot & disown $!