start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

test-coverage:
	composer exec --verbose phpunit -- --coverage-clover build/logs/clover.xml

deploy:
	git push heroku

lint:
	composer run-script phpcs -- --standard=PSR12 public

lint-fix:
	composer phpcbf
