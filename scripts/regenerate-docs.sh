#!/usr/bin/env bash

# remove old stuff just to be fresh..
sudo rm -rf /var/www/tavro/sami/build
sudo rm -rf /var/www/tavro/sami/cache

# regenerate docs
cd /var/www/tavro/sami && sudo php /var/www/tavro/sami/sami update /var/www/tavro/sami/config.php

# restart webserver
sudo service nginx restart && sudo service php7.0-fpm restart