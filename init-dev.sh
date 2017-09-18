#!/usr/bin/env bash

cd /var/www/tavro/api && php bin/console doctrine:database:create
cd /var/www/tavro/api && php bin/console doctrine:migrations:migrate