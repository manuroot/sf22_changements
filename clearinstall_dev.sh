#!/bin/bash
php app/console cache:clear --env=dev --no-debug
php app/console assets:install web --env=dev
php app/console assetic:dump --env=dev  
chown -R www-data:manuroot .
chown -R manuroot:manuroot src/*
chown -R manuroot:manuroot web/*
chown -R www-data:manuroot web/uploads
