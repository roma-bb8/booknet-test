.PHONY: start stop
.SILENT:

start:
	docker-compose up -d --build
	docker exec -ti book-net-php-cli /bin/sh -c "composer update"

test:
	docker exec -ti book-net-php-cli /bin/sh -c "vendor/bin/phpunit"

stop:
	docker-compose down -v
