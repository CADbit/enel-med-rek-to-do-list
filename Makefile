.PHONY: up down build restart logs clean

# Uruchomienie kontenerów
up:
	docker-compose up -d

# Zatrzymanie kontenerów
down:
	docker-compose down

# Zbudowanie kontenerów
build:
	docker-compose build

# Restart kontenerów
restart:
	docker-compose restart

# Wyświetlenie logów
logs:
	docker-compose logs -f

# Czyszczenie (usunięcie kontenerów, wolumenów i obrazów)
clean:
	docker-compose down -v --rmi all

# Wejście do kontenera Laravel
app-bash:
	docker-compose exec app bash

# Wejście do kontenera Vue.js
front-bash:
	docker-compose exec frontend sh

# Instalacja zależności Laravel
app-install:
	docker-compose exec app composer install

# Instalacja zależności Vue.js
front-install:
	docker-compose exec frontend npm install 