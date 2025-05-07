FROM node:20-alpine

WORKDIR /app

# Install curl for healthcheck
RUN apk add --no-cache curl

# Kopiujemy pliki package.json i package-lock.json (jeśli istnieje)
COPY package*.json ./

# Instalacja zależności
RUN npm install

# Kopiujemy resztę plików projektu
COPY . .

EXPOSE 5173

# Uruchamiamy aplikację w trybie deweloperskim z hostem 0.0.0.0
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"] 