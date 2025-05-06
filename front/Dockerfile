FROM node:20-alpine

WORKDIR /app

# Utworzenie nowego projektu Vue.js
RUN npm create vite@latest . -- --template vue

# Instalacja zależności
RUN npm install

COPY package*.json ./

COPY . .

EXPOSE 5173

CMD ["npm", "run", "dev"] 