.RECIPEPREFIX +=

setup:
	git checkout -b develop || git checkout develop
	cp .env.example .env
	docker compose build
	docker compose up -d
	docker compose exec -u ubuntu app /bin/bash -c "composer install && php artisan key:generate && php artisan migrate --seed"
	docker compose exec -u ubuntu app /bin/bash -c "npm install && npm run dev || (rm -rf node_modules && npm install && npm run dev) || exit 1"

teardown:
	docker compose down
	docker compose rm -f
	rm -f .env

down:
	docker compose down

shell:
	docker exec -it -u ubuntu laramentor /bin/bash

