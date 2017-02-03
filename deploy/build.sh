#!/bin/bash
mkdir ~/.ssh
echo $TAVRO_CORE_DEPLOYMENT_KEY > ~/.ssh/id_rsa.tmp # note: assumes base64 encoded ssh key without a passphrase
base64 -d ~/.ssh/id_rsa.tmp > ~/.ssh/id_rsa
chmod 600 ~/.ssh/id_rsa
base64 ~/.ssh/id_rsa
( [ -e "~/.ssh/config" ] || touch "~/.ssh/config" ) && [ ! -w "~/.ssh/config" ] && echo cannot write to ~/.ssh/config && exit 1
echo -e "Host *\n StrictHostKeyChecking no\n UserKnownHostsFile=/dev/null" > ~/.ssh/config
cp api/app/config/parameters.pipelines api/app/config/parameters.yml
sed -ie 's/{DATABASE_DRIVER}/$DATABASE_DRIVER/g' api/app/config/parameters.yml
sed -ie 's/{DATABASE_HOST}/$DATABASE_HOST/g' api/app/config/parameters.yml
sed -ie 's/{DATABASE_PORT}/$DATABASE_PORT/g' api/app/config/parameters.yml
sed -ie 's/{DATABASE_NAME}/$DATABASE_NAME/g' api/app/config/parameters.yml
sed -ie 's/{DATABASE_USER}/$DATABASE_USER/g' api/app/config/parameters.yml
sed -ie 's/{DATABASE_PASSWORD}/$DATABASE_PASSWORD/g' api/app/config/parameters.yml
sed -ie 's/{APP_NAME}/$APP_NAME/g' api/app/config/parameters.yml
sed -ie 's/{APP_VERSION}/$APP_VERSION/g' api/app/config/parameters.yml
sed -ie 's/{APP_EMAIL}/$APP_EMAIL/g' api/app/config/parameters.yml
sed -ie 's/{APP_EMAIL_NAME}/$APP_EMAIL_NAME/g' api/app/config/parameters.yml
sed -ie 's/{MAILER_HOST}/$MAILER_HOST/g' api/app/config/parameters.yml
sed -ie 's/{MAILER_USERNAME}/$MAILER_USERNAME/g' api/app/config/parameters.yml
sed -ie 's/{MAILER_TRANSPORT}/$MAILER_TRANSPORT/g' api/app/config/parameters.yml
sed -ie 's/{MAILER_PORT}/$MAILER_PORT/g' api/app/config/parameters.yml
sed -ie 's/{MAILER_ENCRYPTION}/$MAILER_ENCRYPTION/g' api/app/config/parameters.yml
sed -ie 's/{MAILER_AUTH_MODE}/$MAILER_AUTH_MODE/g' api/app/config/parameters.yml
sed -ie 's/{MAILER_PASSWORD}/$MAILER_PASSWORD/g' api/app/config/parameters.yml
sed -ie 's/{MAILGUN_URL}/$MAILGUN_URL/g' api/app/config/parameters.yml
sed -ie 's/{MAILGUN_API_KEY}/$MAILGUN_API_KEY/g' api/app/config/parameters.yml
sed -ie 's/{DEBUG_EMAIL}/$DEBUG_EMAIL/g' api/app/config/parameters.yml
sed -ie 's/{LOCALE}/$LOCALE/g' api/app/config/parameters.yml
sed -ie 's/{TIMEZONE}/$TIMEZONE/g' api/app/config/parameters.yml
sed -ie 's/{LOGIN_ATTEMPT_MINUTES}/$LOGIN_ATTEMPT_MINUTES/g' api/app/config/parameters.yml
sed -ie 's/{MAX_LOGIN_ATTEMPTS}/$MAX_LOGIN_ATTEMPTS/g' api/app/config/parameters.yml
sed -ie 's/{API_SECRET}/$API_SECRET/g' api/app/config/parameters.yml
sed -ie 's/{APP_HOSTNAME}/$APP_HOSTNAME/g' api/app/config/parameters.yml
sed -ie 's/{API_HOSTNAME}/$API_HOSTNAME/g' api/app/config/parameters.yml
sed -ie 's/{ADMIN_HOSTNAME}/$ADMIN_HOSTNAME/g' api/app/config/parameters.yml
sed -ie 's/{JWT_TOKEN_PASSPHRASE}/$JWT_TOKEN_PASSPHRASE/g' api/app/config/parameters.yml
sed -ie 's/{JWT_TOKEN_TTL}/$JWT_TOKEN_TTL/g' api/app/config/parameters.yml
sed -ie 's/{AWS_SDK_VERSION}/$AWS_SDK_VERSION/g' api/app/config/parameters.yml
sed -ie 's/{AWS_ACCESS_KEY_ID}/$AWS_ACCESS_KEY_ID/g' api/app/config/parameters.yml
sed -ie 's/{AWS_SECRET_ACCESS_KEY}/$AWS_SECRET_ACCESS_KEY/g' api/app/config/parameters.yml
sed -ie 's/{AWS_S3_REGION}/$AWS_S3_REGION/g' api/app/config/parameters.yml
sed -ie 's/{AWS_S3_ASSET_BUCKET}/$AWS_S3_ASSET_BUCKET/g' api/app/config/parameters.yml
sed -ie 's/{AWS_S3_FILE_BUCKET}/$AWS_S3_FILE_BUCKET/g' api/app/config/parameters.yml
sed -ie 's/{SLACK_WEBHOOK_URL}/$SLACK_WEBHOOK_URL/g' api/app/config/parameters.yml
sed -ie 's/{SLACK_CHANNEL_NAME}/$SLACK_CHANNEL_NAME/g' api/app/config/parameters.yml
sed -ie 's/{LOGENTRIES_TOKEN}/$LOGENTRIES_TOKEN/g' api/app/config/parameters.yml
sed -ie 's/{LOGENTRIES_KEY}/$LOGENTRIES_KEY/g' api/app/config/parameters.yml
composer --version
cd api && composer install --no-interaction --prefer-dist --no-scripts --ignore-platform-reqs --no-dev --no-suggest