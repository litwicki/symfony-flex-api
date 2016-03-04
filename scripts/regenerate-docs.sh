#!/usr/bin/env bash

aglio -i /var/www/tavro/tavro.apib --theme-template triple -o src/Tavro/Bundle/AppBundle/Resources/views/Layouts/blueprint.html.twig

sudo php /var/www/tavro/sami/sami update /var/www/tavro/sami/config.php