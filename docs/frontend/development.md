# Rozwój Frontendu

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

## Komponenty

### Tworzenie Nowego Komponentu

1. Utwórz plik w katalogu `src/components/`:
```vue
<template>
  <div class="component-name">
    <!-- Zawartość komponentu -->
  </div>
</template>

<script setup>
// Logika komponentu
</script>

<style scoped>
/* Style komponentu */
</style>
```

2. Zarejestruj komponent w `main.js` lub zaimportuj lokalnie.

### Przykład Komponentu

```vue
<template>
  <div class="task-item">
    <h3>{{ task.title }}</h3>
    <p>{{ task.description }}</p>
    <div class="actions">
      <Button @click="editTask">Edytuj</Button>
      <Button @click="deleteTask">Usuń</Button>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'
import Button from 'primevue/button'

const props = defineProps({
  task: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['edit', 'delete'])

const editTask = () => emit('edit', props.task)
const deleteTask = () => emit('delete', props.task)
</script>

<style scoped>
.task-item {
  padding: 1rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 1rem;
}
</style>
```

## Store (Pinia)

### Tworzenie Store

1. Utwórz plik w katalogu `src/stores/`:
```javascript
import { defineStore } from 'pinia'

export const useTaskStore = defineStore('tasks', {
  state: () => ({
    tasks: [],
    loading: false,
    error: null
  }),
  
  getters: {
    completedTasks: (state) => state.tasks.filter(task => task.status === 'completed')
  },
  
  actions: {
    async fetchTasks() {
      this.loading = true
      try {
        const response = await fetch('/api/tasks')
        this.tasks = await response.json()
      } catch (error) {
        this.error = error.message
      } finally {
        this.loading = false
      }
    }
  }
})
```

### Użycie Store

```vue
<script setup>
import { useTaskStore } from '@/stores/tasks'
import { onMounted } from 'vue'

const taskStore = useTaskStore()

onMounted(() => {
  taskStore.fetchTasks()
})
</script>
```

## Routing

### Dodawanie Nowej Trasy

1. Dodaj trasę w `src/router/index.js`:
```javascript
const routes = [
  // ... istniejące trasy
  {
    path: '/tasks/new',
    name: 'new-task',
    component: () => import('@/views/NewTask.vue')
  }
]
```

2. Użyj routera w komponencie:
```vue
<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const goToNewTask = () => {
  router.push({ name: 'new-task' })
}
</script>
```

## API

### Konfiguracja Axios

1. Utwórz instancję Axios w `src/api/index.js`:
```javascript
import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})

export default api
```

### Użycie API

```javascript
import api from '@/api'

// Pobieranie zadań
const fetchTasks = async () => {
  try {
    const response = await api.get('/tasks')
    return response.data
  } catch (error) {
    console.error('Błąd podczas pobierania zadań:', error)
    throw error
  }
}

// Tworzenie zadania
const createTask = async (task) => {
  try {
    const response = await api.post('/tasks', task)
    return response.data
  } catch (error) {
    console.error('Błąd podczas tworzenia zadania:', error)
    throw error
  }
}
```

## Hot Module Replacement (HMR)

Vite zapewnia automatyczne odświeżanie komponentów podczas rozwoju. Zmiany w plikach są natychmiast widoczne w przeglądarce.

## Debugowanie

### Vue DevTools

1. Zainstaluj rozszerzenie Vue DevTools w przeglądarce
2. Otwórz narzędzia deweloperskie (F12)
3. Przejdź do zakładki Vue

### Console Logging

```javascript
// Podstawowe logowanie
console.log('Wartość:', value)

// Grupowanie logów
console.group('Nazwa grupy')
console.log('Log 1')
console.log('Log 2')
console.groupEnd()

// Pomiar czasu
console.time('Nazwa operacji')
// ... kod do zmierzenia
console.timeEnd('Nazwa operacji')
```

## Optymalizacja

### Lazy Loading

```javascript
// Lazy loading komponentów
const TaskList = () => import('@/components/TaskList.vue')
```

### Code Splitting

```javascript
// Code splitting w routerze
const routes = [
  {
    path: '/tasks',
    component: () => import('@/views/Tasks.vue')
  }
]
```

## Deployment

### Budowanie Produkcyjne

```bash
make front-build
```

### Sprawdzanie Bundle

```bash
docker-compose exec frontend npm run build -- --report
```

## Best Practices

1. Używaj Composition API z `<script setup>`
2. Dziel komponenty na mniejsze, reużywalne części
3. Używaj TypeScript dla lepszej kontroli typów
4. Pisz testy jednostkowe
5. Używaj ESLint i Prettier
6. Dokumentuj komponenty
7. Używaj slotów dla elastycznych komponentów
8. Implementuj lazy loading
9. Używaj store dla globalnego stanu
10. Obsługuj błędy i stany ładowania
