#!/bin/bash

api_path=/var/www/tavro/api
core_fixture_path=$api_path/src/Tavro/Bundle/CoreBundle/DataFixtures
api_fixture_path=$api_path/src/Tavro/Bundle/ApiBundle/DataFixtures

# Execute Migrations in a Symfony application
sudo bash /var/www/tavro/scripts/reset-clean.sh
sudo php $api_path/bin/console doctrine:fixtures:load --fixtures=$api_fixture_path/Demo --append