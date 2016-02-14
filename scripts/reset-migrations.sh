#!/bin/bash

api_path=/var/www/tavro/api

cd $api_path/app/DoctrineMigrations
find . -type f -not -name "Version1.php" -exec rm -rf {} \;

cd $api_path
sudo php bin/console doctrine:generate:entities TavroCoreBundle
sudo php bin/console doctrine:database:drop --force
sudo php bin/console doctrine:database:create
sudo php bin/console doctrine:migrations:diff
sudo php bin/console cache:clear