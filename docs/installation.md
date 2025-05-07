# Wymagania i Instalacja

## Wymagania Systemowe

- Docker
- Docker Compose
- Make (opcjonalnie, ale zalecane)
- Node.js 20+ (tylko dla rozwoju frontendu)

## Proces Instalacji

1. Sklonuj repozytorium:
```bash
git clone [URL_REPOZYTORIUM]
cd enel-med-rek
```

2. Uruchom instalację:
```bash
make install
```

To polecenie wykonuje następujące kroki:
- Zbuduje i uruchomi kontenery Docker
- Zainstaluje wszystkie zależności
- Skonfiguruje bazę danych
- Wygeneruje klucz aplikacji

## Dostęp do Aplikacji

Po instalacji aplikacja będzie dostępna pod następującymi adresami:

- Frontend: http://localhost:8000
- API: http://localhost:8000/api
- Vue Dev Server: http://localhost:5173 (tylko w trybie deweloperskim)

## Weryfikacja Instalacji

Aby sprawdzić, czy instalacja przebiegła pomyślnie:

1. Sprawdź status kontenerów:
```bash
make health
```

2. Sprawdź logi:
```bash
make logs
```

3. Uruchom testy:
```bash
make test
```

## Następne Kroki

Po pomyślnej instalacji możesz:
1. Rozpocząć rozwój aplikacji - patrz [Rozwój Frontendu](frontend/development.md)
2. Skonfigurować środowisko - patrz [Konfiguracja](frontend/configuration.md)
3. Zapoznać się z dostępnymi komendami - patrz [Podstawowe Komendy](commands.md) 