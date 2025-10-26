#!/bin/bash

echo "ﾋﾟｷﾞｭﾁｭﾐｭﾘﾐﾋﾟｭﾁｭｸﾐﾘｭﾋﾟｭﾆｭｲﾐﾘｭﾘｭ↑"

docker compose up -d --build
sleep 10


docker compose exec app composer install --no-interaction
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
docker compose exec app chown -R www-data:www-data /var/www/html/storage
docker compose exec app chmod -R 775 /var/www/html/storage

echo ""
echo "う、疲れたピョン･･･。FetchKiji API のセットアップが完了したピョン！🐰（←酩酊している）"
echo ""

