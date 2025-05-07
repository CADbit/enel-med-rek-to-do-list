# Enel-med-rek - System Zarządzania Zadaniami

Aplikacja do zarządzania zadaniami zbudowana przy użyciu Laravel (backend) i Vue.js (frontend). System umożliwia tworzenie, edycję, usuwanie i śledzenie statusu zadań.

## Spis Treści

1. [Wymagania i Instalacja](docs/installation.md)
2. [Podstawowe Komendy](docs/commands.md)
3. [Struktura Projektu](docs/project-structure.md)
4. [Frontend](docs/frontend/README.md)
   - [Konfiguracja](docs/frontend/configuration.md)
   - [Rozwój](docs/frontend/development.md)
   - [Testy](docs/frontend/testing.md)
5. [Backend](docs/backend/README.md)
   - [Konfiguracja](docs/backend/configuration.md)
   - [API](docs/backend/api.md)
   - [Testy](docs/backend/testing.md)
6. [Rozwiązywanie Problemów](docs/troubleshooting.md)

## Szybki Start

1. Sklonuj repozytorium:
```bash
git clone [URL_REPOZYTORIUM]
cd enel-med-rek
```

2. Pierwsza instalacja (wykonaj tylko raz):
```bash
make first-install
```

3. Uruchomienie aplikacji (po pierwszej instalacji):
```bash
make run
```

4. Zatrzymanie aplikacji:
```bash
make stop
```

## Dostępne Komendy Make

### Podstawowe Operacje
- `make run` - Uruchomienie aplikacji
- `make stop` - Zatrzymanie aplikacji
- `make restart` - Restart aplikacji
- `make logs` - Wyświetlenie logów

### Instalacja i Konfiguracja
- `make first-install` - Pełna instalacja (tylko przy pierwszym uruchomieniu)
- `make clean` - Czyszczenie środowiska (usuwa kontenery, wolumeny i obrazy)
- `make build` - Zbudowanie kontenerów

### Wejście do Kontenerów
- `make app-bash` - Wejście do kontenera Laravel
- `make front-bash` - Wejście do kontenera Vue.js

### Baza Danych
- `make db-migrate` - Wykonanie migracji
- `make db-seed` - Wypełnienie bazy danymi testowymi
- `make db-fresh` - Reset bazy danych z danymi testowymi

### Laravel
- `make app-cache-clear` - Czyszczenie cache
- `make app-config-cache` - Cache konfiguracji
- `make app-route-cache` - Cache routingu
- `make app-view-cache` - Cache widoków
- `make app-optimize` - Optymalizacja Laravel
- `make app-logs` - Wyświetlenie logów Laravel

### Vue.js
- `make front-dev` - Uruchomienie serwera deweloperskiego
- `make front-build` - Zbudowanie aplikacji Vue.js
- `make front-lint` - Sprawdzenie kodu linterem

### Testy
- `make test` - Uruchomienie wszystkich testów
- `make app-test` - Testy Laravel
- `make front-test` - Testy Vue.js

## Dostęp do Aplikacji

Po uruchomieniu aplikacja będzie dostępna pod adresami:
- Frontend: http://localhost:8000
- API: http://localhost:8000/api
- Vue Dev Server: http://localhost:5173 (tylko w trybie deweloperskim)

## Dokumentacja API

Dostępne są dwa formaty dokumentacji API:

1. Interaktywna dokumentacja Swagger UI:
```
http://localhost:8000/api/documentation
```

2. Dokumentacja w formacie JSON:
```
http://localhost:8000/docs
```

## Wsparcie

W przypadku problemów, zapoznaj się z sekcją [Rozwiązywanie Problemów](docs/troubleshooting.md). 