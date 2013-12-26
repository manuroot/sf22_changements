#!/bin/bash
php app/console cache:clear --env=dev --no-debug
php app/console assets:install web --env=dev
php app/console assetic:dump --env=dev -v 
chown -R www-data:manuroot ../Symfony_changements
chown -R manuroot:manuroot ../Symfony_changements/src/*
chown -R manuroot:manuroot ../Symfony_changements/web/*
chown -R www-data:manuroot ../Symfony_changements/web/uploads
