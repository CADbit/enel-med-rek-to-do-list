# Podstawowe Komendy

## Rozwój

```bash
make dev          # Uruchomienie środowiska deweloperskiego
make front-dev    # Uruchomienie serwera deweloperskiego Vue.js
make logs         # Wyświetlenie logów
```

## Frontend

```bash
make front-bash   # Wejście do kontenera frontend
make front-lint   # Uruchomienie lintera
make front-build  # Zbudowanie wersji produkcyjnej
make front-test   # Uruchomienie testów
```

## Backend

```bash
make app-bash     # Wejście do kontenera backend
make app-test     # Uruchomienie testów
make app-logs     # Wyświetlenie logów
```

## Testy

```bash
make test         # Uruchomienie wszystkich testów
make app-test     # Uruchomienie testów Laravel
make front-test   # Uruchomienie testów Vue.js
```

## Baza Danych

```bash
make db-migrate   # Wykonanie migracji
make db-seed      # Wypełnienie bazy danymi testowymi
make db-fresh     # Odświeżenie bazy danych
```

## Konserwacja

```bash
make restart      # Restart kontenerów
make down         # Zatrzymanie kontenerów
make up           # Uruchomienie kontenerów
make clean        # Czyszczenie środowiska
```

## Laravel

```bash
make app-cache-clear    # Czyszczenie cache
make app-config-cache   # Cache konfiguracji
make app-route-cache    # Cache routingu
make app-view-cache     # Cache widoków
make app-key           # Generowanie klucza aplikacji
make app-optimize      # Optymalizacja Laravel
make app-storage-link  # Tworzenie linku do storage
```

## Docker

```bash
make docker-prune      # Czyszczenie nieużywanych zasobów Docker
make docker-clean      # Pełne czyszczenie środowiska Docker
```

## Health Check

```bash
make health           # Sprawdzenie stanu usług
```

Więcej szczegółów o poszczególnych komendach znajdziesz w odpowiednich sekcjach dokumentacji:
- [Rozwój Frontendu](frontend/development.md)
- [Testy Frontendu](frontend/testing.md)
- [Konfiguracja Backendu](backend/configuration.md)
- [Testy Backendu](backend/testing.md) 