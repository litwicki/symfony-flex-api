#!/bin/bash

# Move to the API directory
cd /var/www/tavro/api

# Clear application cache
php bin/console cache:clear

# Bootstrap the Application
php /var/www/tavro/api/vendor/sensio/distribution-bundle/Resources/bin/build_bootstrap.php

# @TODO: call the release command with the revision # from AWS
# @TODO: send SNS to all subscribers