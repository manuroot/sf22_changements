#!/bin/bash
php app/console cache:clear --env=prod --no-debug
php app/console assets:install web --env=prod
php app/console assetic:dump --env=prod -v
chown -R www-data:manuroot ../Symfony_changements
chown -R manuroot:manuroot ../Symfony_changements/src/*
chown -R manuroot:manuroot ../Symfony_changements/web/*
