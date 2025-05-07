# Konfiguracja Frontendu

## Zmienne Środowiskowe

Utwórz plik `.env` w katalogu `front/`:

```env
VITE_API_URL=http://localhost:8000/api
```

## Zależności

### Wymagane Pakiety

- Node.js 20+
- npm lub yarn

### Instalacja Zależności

```bash
# Instalacja zależności
make front-install

# Lub bezpośrednio
docker-compose exec frontend npm install
```

## Konfiguracja Vite

Plik `vite.config.js` zawiera podstawową konfigurację:

```javascript
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src')
    }
  },
  server: {
    host: true,
    port: 5173
  }
})
```

## Konfiguracja ESLint

Plik `.eslintrc.js` zawiera reguły lintingu:

```javascript
module.exports = {
  root: true,
  env: {
    node: true
  },
  extends: [
    'plugin:vue/vue3-essential',
    'eslint:recommended'
  ],
  parserOptions: {
    parser: '@babel/eslint-parser'
  },
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off'
  }
}
```

## Konfiguracja Vitest

Plik `vitest.config.js` zawiera konfigurację testów:

```javascript
import { defineConfig } from 'vitest/config'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [vue()],
  test: {
    environment: 'jsdom',
    globals: true,
    coverage: {
      reporter: ['text', 'json', 'html']
    }
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src')
    }
  }
})
```

## Konfiguracja PrimeVue

W pliku `main.js`:

```javascript
import { createApp } from 'vue'
import PrimeVue from 'primevue/config'
import App from './App.vue'

const app = createApp(App)
app.use(PrimeVue)
app.mount('#app')
```

## Konfiguracja Pinia

W pliku `main.js`:

```javascript
import { createPinia } from 'pinia'

const app = createApp(App)
app.use(createPinia())
```

## Konfiguracja Routera

W pliku `src/router/index.js`:

```javascript
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/Home.vue')
  },
  {
    path: '/tasks',
    name: 'tasks',
    component: () => import('@/views/Tasks.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
```

## Rozwiązywanie Problemów

### Problemy z Konfiguracją

1. Sprawdź wersje pakietów w `package.json`
2. Wyczyść cache npm:
   ```bash
   docker-compose exec frontend npm cache clean --force
   ```
3. Usuń node_modules i zainstaluj ponownie:
   ```bash
   docker-compose exec frontend rm -rf node_modules
   make front-install
   ```

### Problemy z Hot Module Replacement (HMR)

1. Sprawdź konfigurację Vite
2. Upewnij się, że port 5173 jest dostępny
3. Sprawdź logi:
   ```bash
   docker-compose logs frontend
   ```

### Problemy z ESLint

1. Sprawdź konfigurację w `.eslintrc.js`
2. Uruchom linter z flagą debug:
   ```bash
   docker-compose exec frontend npm run lint -- --debug
   ```

### Problemy z Testami

1. Sprawdź konfigurację Vitest
2. Uruchom testy z flagą debug:
   ```bash
   docker-compose exec frontend npm run test -- --debug
   ``` 