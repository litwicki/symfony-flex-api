#!/usr/bin/env bash

sudo rm -rf /var/www/tavro/sami/build
sudo rm -rf /var/www/tavro/sami/cache
sudo php /var/www/tavro/sami/sami update /var/www/tavro/sami/config.php