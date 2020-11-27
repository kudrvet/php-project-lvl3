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

test-coverage-composer:
	composer exec --verbose phpunit -- --coverage-clover build/logs/clover.xml

test-coverage:
	php artisan test --coverage-clover logs/coverage/clover.xml

push-local-coverage:
	./test-reporter format-coverage -t clover logs/coverage/clover.xml
	./test-reporter upload-coverage

deploy:
	git push heroku

lint:
	composer run-script phpcs -- --standard=PSR12 public

lint-fix:
	composer phpcbf
