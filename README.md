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

2. Uruchom instalację:
```bash
make install
```

3. Aplikacja będzie dostępna pod adresem:
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