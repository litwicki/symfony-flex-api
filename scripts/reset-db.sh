#!/bin/bash

api_path=/var/www/tavro/api
core_fixture_path=$api_path/src/Tavro/Bundle/CoreBundle/DataFixtures
api_fixture_path=$api_path/src/Tavro/Bundle/ApiBundle/DataFixtures

# Execute Migrations in a Symfony application
sudo php $api_path/bin/console doctrine:database:drop --force
sudo php $api_path/bin/console doctrine:database:create
sudo php $api_path/bin/console doctrine:migrations:migrate --no-interaction

sudo php $api_path/bin/console doctrine:fixtures:load --fixtures=$core_fixture_path/ORM/Core --append
sudo php $api_path/bin/console doctrine:fixtures:load --fixtures=$api_fixture_path/Demo --append