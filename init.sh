#!/bin/bash

for i in "$@"
do
case $i in
    -w=*|--webdir=*)
    WEBROOT="${i#*=}"

    ;;
    -e=*|--environment=*)
    ENV="${i#*=}"
    ;;

    --default)
    DEFAULT=YES
    ;;
    
    *)
    printf "\n=============================================================================\n\n"
    printf "ERROR:\tOnly [-e|--environment] and [-w|--webdir] are valid arguments!"
    printf "\n\n\texample: ./init.sh --env=dev --webdir=/var/www/tavro"
    printf "\n\n=============================================================================\n\n"
    exit;
    ;;
esac
done

# Assign default values
APP_WEBROOT=${WEBROOT:=/var/www/tavro/api}
APP_ENVIRONMENT=${ENV:=dev}

echo APP_WEBROOT = ${APP_WEBROOT}
echo APP_ENVIRONMENT = ${APP_ENVIRONMENT}

REGEX="(dev|test|prod)$"
VALID_ENV=$(expr match $APP_ENVIRONMENT $REGEX)

if [[ ! $VALID_ENV ]]
then
    printf "\n=============================================================================\n\n"
    printf "ERROR:\tOnly acceptable environment values are: dev, test, prod"
    printf "\n\n=============================================================================\n\n"
    exit;
fi

# Create the database if it doesn't exist..
php $WEBROOT/bin/console doctrine:database:create --if-not-exists

# Execute all migrations..
php $WEBROOT/bin/console doctrine:migrations:migrate

# Execute fixtures for the environment
# ** MAKE SURE TO INCLUDE --append OR YOU WILL LOSE EVERYTHING! **
php $WEBROOT/bin/console doctrine:fixtures:load --fixtures=$WEBROOT/vendor/zoadilack/tavro-core/Tavro/Bundle/CoreBundle/DataFixtures/Core --append

# If we're in DEV, then also run the dev fixtures
if [ "$ENVIRONMENT" = "dev" ]; then
    php $WEBROOT/bin/console doctrine:fixtures:load --fixtures=$WEBROOT/vendor/zoadilack/tavro-core/Tavro/Bundle/CoreBundle/DataFixtures/Dev --append
fi

php $WEBROOT/bin/console cache:clear --env=${APP_ENVIRONMENT} --no-warmup
