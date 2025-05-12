# Laravel Sail Makefile
# Helper commands to work with Laravel Sail

.PHONY: up down ssh build restart pint

# Default command when just running 'make'
default: help

# Start the Sail environment
up:
	./vendor/bin/sail up -d

# Stop the Sail environment
down:
	./vendor/bin/sail down

# SSH into the application container
ssh:
	./vendor/bin/sail shell

# Restart the containers
restart: down up

pint:
	./vendor/bin/pint -v

phpstan:
	./vendor/bin/phpstan analyse --memory-limit=2G


test-workflow:
	act  -P ubuntu-latest=kirschbaumdevelopment/laravel-test-runner:8.4 --env-file .env.ci

test:
	php artisan test --parallel --coverage --min=80

build-android:
	php artisan native:run android

# Display help information
help:
	@echo ""
	@echo "Available commands:"
	@echo "make up        - Start the Sail environment (-d detached mode)"
	@echo "make down      - Stop the Sail environment"
	@echo "make ssh       - SSH into the application container"
	@echo "make build     - Rebuild the containers"
	@echo "make pint      - Run Laravel Pint - opinionated PHP code style fixer"
	@echo "make help      - Display this help information"
	@echo "make test-workflow - Run the GitHub Actions workflow locally"
	@echo "make test      - Run the tests"
	@echo "make build-android   - Build the Android app"
	@echo ""