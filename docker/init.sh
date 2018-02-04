#!/bin/bash

# ------------------------------------------------------------------------------------------------
# @TODO:    Build a failsafe for this to only execute in non production environments
#           or better yet, clean ths up with a proper SRE expert who can build this more better!
# ------------------------------------------------------------------------------------------------

for i in "$@"
do
case $i in
    -w=*|--webdir=*)
    WEBROOT="${i#*=}"
    ;;

    -e=*|--environment=*)
    ENV="${i#*=}"
    ;;

    -p=*|--pass-phrase=*)
    TOKEN_PASSWORD="${i#*=}"
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
APP_WEBROOT=${WEBROOT:=/var/www/html}
APP_ENVIRONMENT=${ENV:=dev}
JWT_TOKEN_PASSPHRASE=${TOKEN_PASSWORD:=tavro}
APP_KEYS_DIR=$APP_WEBROOT/var/keys

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

# Drop the database to rebuild it from scratch
php $APP_WEBROOT/bin/console doctrine:database:drop --force

# Create the database if it doesn't exist..
php $APP_WEBROOT/bin/console doctrine:database:create --if-not-exists

# Execute all migrations..
php $APP_WEBROOT/bin/console doctrine:migrations:migrate --no-interaction

# Execute fixtures for the environment
# ** MAKE SURE TO INCLUDE --append OR YOU WILL LOSE EVERYTHING! **
php $APP_WEBROOT/bin/console doctrine:fixtures:load --fixtures=$APP_WEBROOT/src/DataFixtures/Core --append

# If we're in DEV, then also run the dev fixtures
if [ "$APP_ENVIRONMENT" == "dev" ]; then
    php $APP_WEBROOT/bin/console doctrine:fixtures:load --fixtures=$APP_WEBROOT/src/DataFixtures/Dev --append
fi

# Remove keys dir if it exists
if [ -d "$APP_KEYS_DIR" ]; then rm -Rf $APP_KEYS_DIR; fi

# Create keys dir
mkdir $APP_KEYS_DIR

# Setup permissions for keys dir
chmod -R 0777 $APP_KEYS_DIR

# Install the public/private keys for jwt tokens
cd $APP_KEYS_DIR && openssl genrsa -out private.pem -aes256 -passout pass:$JWT_TOKEN_PASSPHRASE 4096
cd $APP_KEYS_DIR && openssl rsa -passin pass:$JWT_TOKEN_PASSPHRASE -pubout -in private.pem -out public.pem

# Clear the caches!
php $APP_WEBROOT/bin/console cache:clear --env=${APP_ENVIRONMENT} --no-warmup
