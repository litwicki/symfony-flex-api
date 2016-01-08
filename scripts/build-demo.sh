#!/bin/bash

# Include the global config file
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
source $DIR/config

sudo php /var/www/$APPNAME/www/app/console doctrine:fixtures:load --fixtures=/var/www/$APPNAME/www/src/Tavro/Bundle/CoreBundle/DataFixtures/ORM/Dev/People --append
sudo php /var/www/$APPNAME/www/app/console doctrine:fixtures:load --fixtures=/var/www/$APPNAME/www/src/Tavro/Bundle/CoreBundle/DataFixtures/ORM/Dev/Organizations --append
sudo php /var/www/$APPNAME/www/app/console doctrine:fixtures:load --fixtures=/var/www/$APPNAME/www/src/Tavro/Bundle/CoreBundle/DataFixtures/ORM/Dev/Nodes --append