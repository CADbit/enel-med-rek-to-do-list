.PHONY: up down build restart logs clean run stop first-install

# Uruchomienie kontenerów
up:
	docker compose up -d

# Zatrzymanie kontenerów
down:
	docker compose down

# Zbudowanie kontenerów
build:
	docker compose build

# Restart kontenerów
restart:
	docker compose restart

# Wyświetlenie logów
logs:
	docker compose logs -f

# Czyszczenie (usunięcie kontenerów, wolumenów i obrazów)
clean:
	docker compose down -v --rmi all

# Uruchomienie i zatrzymanie aplikacji (bez migracji i seedów)
run: up
	@echo "Application started. Use 'make stop' to stop it."

stop: down
	@echo "Application stopped."

# Pierwsza instalacja (pełna konfiguracja)
first-install: env-copy
	@echo "Building containers..."
	@docker compose build
	@echo "Starting containers..."
	@docker compose up -d
	@echo "Waiting for containers to be ready..."
	@sleep 5
	@echo "Installing Laravel dependencies..."
	@make app-install
	@echo "Installing Vue.js dependencies..."
	@make front-install
	@echo "Setting up Laravel..."
	@make app-key
	@make app-storage-link
	@make db-migrate
	@echo "Seeding database..."
	@make db-seed
	@echo "First installation completed! You can now use 'make run' to start the application."

# Wejście do kontenera Laravel
app-bash:
	docker compose exec app bash

# Wejście do kontenera Vue.js
front-bash:
	docker compose exec frontend sh

# Instalacja zależności Laravel
app-install:
	docker compose exec app composer install

# Instalacja zależności Vue.js
front-install:
	docker compose exec frontend npm install

# Copy environment file
env-copy:
	@if [ ! -f .env ]; then \
		cp app/laravel/.env.example .env; \
		echo "Created .env file from app/laravel/.env.example"; \
	else \
		echo ".env file already exists"; \
	fi
	@if [ ! -f app/laravel/.env ]; then \
		cp app/laravel/.env.example app/laravel/.env; \
		echo "Created app/laravel/.env file from app/laravel/.env.example"; \
	else \
		echo "app/laravel/.env file already exists"; \
	fi

# Instalacja wszystkich zależności (Laravel + Vue.js)
install: env-copy build up
	@echo "Waiting for containers to be ready..."
	@sleep 5
	@echo "Installing Laravel dependencies..."
	@make app-install
	@echo "Installing Vue.js dependencies..."
	@make front-install
	@echo "Setting up Laravel..."
	@make app-key
	@make app-storage-link
	@make db-migrate
	@echo "Seeding database..."
	@make db-seed
	@echo "Installation completed!"

# Uruchomienie testów Laravel
app-test:
	docker compose exec app php artisan test

# Uruchomienie testów Vue.js
front-test:
	docker compose exec frontend npm run test

# Uruchomienie wszystkich testów
test: app-test front-test

# Uruchomienie lintera Vue.js
front-lint:
	docker compose exec frontend npm run lint

# Uruchomienie builda Vue.js
front-build:
	docker compose exec frontend npm run build

# Uruchomienie dev serwera Vue.js
front-dev:
	docker compose exec frontend npm run dev

# Database operations
db-migrate:
	docker compose exec app php artisan migrate

db-seed:
	docker compose exec app php artisan db:seed

db-fresh:
	docker compose exec app php artisan migrate:fresh --seed

# Laravel specific commands
app-cache-clear:
	docker compose exec app php artisan cache:clear

app-config-cache:
	docker compose exec app php artisan config:cache

app-route-cache:
	docker compose exec app php artisan route:cache

app-view-cache:
	docker compose exec app php artisan view:cache

# Generate application key if not set
app-key:
	docker compose exec app php artisan key:generate

# Optimize Laravel
app-optimize:
	docker compose exec app php artisan optimize

# Show Laravel logs
app-logs:
	docker compose exec app tail -f storage/logs/laravel.log

# Create storage link
app-storage-link:
	docker compose exec app php artisan storage:link

# Development helpers
dev: up
	@echo "Starting development environment..."
	@make front-dev

# Production helpers
prod-build: front-build
	@echo "Building for production..."
	@make app-optimize

# Docker helpers
docker-prune:
	docker system prune -f

docker-clean: clean docker-prune
	@echo "Docker environment cleaned"

# Health check
health:
	@echo "Checking services health..."
	@docker compose ps 