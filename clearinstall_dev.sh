#!/bin/bash
php app/console cache:clear --env=dev --no-debug
php app/console assets:install web --env=dev
php app/console assetic:dump --env=dev
