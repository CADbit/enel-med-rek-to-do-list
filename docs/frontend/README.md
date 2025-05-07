# Frontend

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

### Użycie API w Aplikacji

```javascript
import api from '@/api'

// Przykład użycia API
const fetchTasks = async () => {
  try {
    const response = await api.get('/tasks')
    return response.data
  } catch (error) {
    console.error('Błąd podczas pobierania zadań:', error)
    throw error
  }
}
```

## Struktura Projektu

```
front/
├── src/
│   ├── components/    # Komponenty Vue
│   ├── stores/        # Store Pinia
│   ├── views/         # Widoki
│   ├── router/        # Konfiguracja routingu
│   └── assets/        # Zasoby statyczne
├── public/            # Statyczne pliki
└── tests/             # Testy Vue.js
```

## Rozpoczęcie Pracy

1. Uruchom środowisko deweloperskie:
```bash
make front-dev
```

2. Otwórz przeglądarkę pod adresem:
```
http://localhost:5173
```

## Dokumentacja

- [Konfiguracja](configuration.md) - szczegółowa konfiguracja frontendu
- [Rozwój](development.md) - instrukcje rozwoju i best practices
- [Testy](testing.md) - kompletny przewodnik po testach

## Wymagania

- Node.js 20+
- npm lub yarn
- Docker i Docker Compose

## Dostępne Skrypty

```bash
# Instalacja zależności
make front-install

# Uruchomienie serwera deweloperskiego
make front-dev

# Uruchomienie testów
make front-test

# Budowanie produkcyjne
make front-build

# Uruchomienie lintera
make front-lint
```

## Integracja z Backendem

Aplikacja frontendowa komunikuje się z backendem poprzez REST API. Wszystkie endpointy są udokumentowane w dokumentacji Swagger UI dostępnej pod adresem `http://localhost:8000/api/documentation`.

### Konfiguracja API

Plik `.env` w katalogu `front/` powinien zawierać:

```env
VITE_API_URL=http://localhost:8000/api
```

### Uwierzytelnianie

API wymaga uwierzytelnienia poprzez token JWT. Token powinien być przekazywany w nagłówku `Authorization`:

```javascript
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
  }
})
```

## Rozwiązywanie Problemów

W przypadku problemów z dostępem do API:

1. Sprawdź, czy backend jest uruchomiony:
```bash
make health
```

2. Sprawdź logi backendu:
```bash
make logs
```

3. Sprawdź, czy dokumentacja API jest dostępna pod adresem `http://localhost:8000/api/documentation`

4. Sprawdź konfigurację CORS w backendzie

5. Sprawdź logi frontendu:
```bash
docker-compose logs frontend
``` 