test:
	docker-compose exec app php artisan migrate:reset
	docker-compose exec app php artisan migrate
	docker-compose exec app php artisan db:seed

custom-composer-install:
	# DB_HOST=127.0.0.1 composer require honeystone/laravel-seo