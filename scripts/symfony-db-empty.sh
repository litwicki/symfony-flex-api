#!/bin/bash

# Include the global config file
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
source $DIR/config

echo "CLEARING ALL $APPNAME* DATABASE SCHEMAS"
mysql -uroot -p$DBPASSWD -e 'show databases' | grep $DBNAME* | xargs -I "@@" mysql -uroot -p$DBPASSWD -e "DROP database \`@@\`"
echo 'SUCCESS - rebuilding Application databases'
mysql -uroot -p$DBPASSWD -e "grant all on *.* to 'root'@'$MACHINE_IP' identified by '$DBPASSWD'";
mysql -uroot -p$DBPASSWD -e "grant all on *.* to 'root'@'192.168.50.1' identified by '$DBPASSWD'";

# Execute Migrations in a Symfony application
sudo php /var/www/$APPNAME/www/app/console doctrine:database:create
sudo php /var/www/$APPNAME/www/app/console doctrine:migrations:migrate --no-interaction

sudo php /var/www/$APPNAME/www/app/console doctrine:fixtures:load --fixtures=/var/www/$APPNAME/www/src/Camelot/Bundle/CoreBundle/DataFixtures/ORM/Core/v1/ --append

echo 'DEVELOPMENT ENVIRONMENT COMPLETE!'