#!/bin/bash
php app/console cache:clear --env=prod --no-debug
php app/console assets:install web --env=prod
php app/console assetic:dump --env=prod
