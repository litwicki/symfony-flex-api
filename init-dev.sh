#!/usr/bin/env bash

# Create the database if it doesn't exist..
cd /var/www/tavro/api && php bin/console doctrine:database:create --if-not-exists

# Execute all migrations..
cd /var/www/tavro/api && php bin/console doctrine:migrations:migrate

# Execute fixtures for the environment
# ** MAKE SURE TO INCLUDE --append OR YOU WILL LOSE EVERYTHING! **
php bin/console doctrine:fixtures:load --fixtures=/var/www/tavro/api/vendor/zoadilack/tavro-core/Tavro/Bundle/CoreBundle/DataFixtures/Core --append

# Do we want to include DEV test data?
# php bin/console doctrine:fixtures:load --fixtures=/var/www/tavro/api/vendor/zoadilack/tavro-core/Tavro/Bundle/CoreBundle/DataFixtures/Dev --append