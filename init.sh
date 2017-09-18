#!/usr/bin/env bash

while [[ $# -gt 1 ]]
do
key="$1"

case $key in
    -e|--env)
    ENVIRONMENT="$2"
    shift # past argument
    ;;
    --default)
    DEFAULT=YES
    ;;
    *)
    # unknown option
    ;;
esac
shift # past argument or value
done

if [ -n "$ENVIRONMENT" ]; then
    echo ENVIRONMENT  = "${ENVIRONMENT}"
else
    ENVIRONMENT="prod"
fi

# Create the database if it doesn't exist..
cd /var/www/tavro/api && php bin/console doctrine:database:create --if-not-exists

# Execute all migrations..
cd /var/www/tavro/api && php bin/console doctrine:migrations:migrate

# Execute fixtures for the environment
# ** MAKE SURE TO INCLUDE --append OR YOU WILL LOSE EVERYTHING! **
php bin/console doctrine:fixtures:load --fixtures=/var/www/tavro/api/vendor/zoadilack/tavro-core/Tavro/Bundle/CoreBundle/DataFixtures/Core --append

# If we're in DEV, then also run the dev fixtures
if [ "$ENVIRONMENT" = "dev" ]; then
    php bin/console doctrine:fixtures:load --fixtures=/var/www/tavro/api/vendor/zoadilack/tavro-core/Tavro/Bundle/CoreBundle/DataFixtures/Dev --append
fi
