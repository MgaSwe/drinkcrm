up:
\texport UID=$(id -u) GID=$(id -g) && docker compose up -d
down:
\tdocker compose down
logs:
\tdocker compose logs -f --tail=100
bash:
\tdocker compose exec php sh
migrate:
\tdocker compose exec php php artisan migrate
seed:
\tdocker compose exec php php artisan db:seed
test:
\tdocker compose exec php php artisan test
