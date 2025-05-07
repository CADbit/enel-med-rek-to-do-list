# Testy Frontendu

## Konfiguracja

### Vitest

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

### Testing Library

```javascript
import { render, screen, fireEvent } from '@testing-library/vue'
import { describe, it, expect } from 'vitest'
```

## Uruchamianie Testów

```bash
# Uruchomienie wszystkich testów
make front-test

# Uruchomienie testów z raportem pokrycia
docker-compose exec frontend npm run test:coverage

# Uruchomienie testów w trybie watch
docker-compose exec frontend npm run test:watch
```

## Testy Komponentów

### Podstawowy Test

```javascript
import { render, screen } from '@testing-library/vue'
import TaskList from '@/components/TaskList.vue'
import { describe, it, expect } from 'vitest'

describe('TaskList', () => {
  it('renders empty state correctly', () => {
    render(TaskList, {
      props: {
        tasks: []
      }
    })
    
    expect(screen.getByText('Brak zadań')).toBeInTheDocument()
  })
})
```

### Test z Props

```javascript
describe('TaskItem', () => {
  it('renders task details', () => {
    const task = {
      id: 1,
      title: 'Test Task',
      description: 'Test Description'
    }
    
    render(TaskItem, {
      props: {
        task
      }
    })
    
    expect(screen.getByText('Test Task')).toBeInTheDocument()
    expect(screen.getByText('Test Description')).toBeInTheDocument()
  })
})
```

### Test Interakcji

```javascript
describe('TaskForm', () => {
  it('emits submit event with form data', async () => {
    const { emitted } = render(TaskForm)
    
    await fireEvent.update(screen.getByLabelText('Tytuł'), 'New Task')
    await fireEvent.update(screen.getByLabelText('Opis'), 'Task Description')
    await fireEvent.click(screen.getByText('Zapisz'))
    
    expect(emitted().submit[0][0]).toEqual({
      title: 'New Task',
      description: 'Task Description'
    })
  })
})
```

## Testy Store (Pinia)

### Mock Store

```javascript
import { setActivePinia, createPinia } from 'pinia'
import { useTaskStore } from '@/stores/tasks'

describe('TaskStore', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })
  
  it('fetches tasks', async () => {
    const store = useTaskStore()
    await store.fetchTasks()
    expect(store.tasks).toHaveLength(2)
  })
})
```

## Testy Routera

### Mock Router

```javascript
import { createRouter, createWebHistory } from 'vue-router'
import { render } from '@testing-library/vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/tasks',
      component: { template: '<div>Tasks</div>' }
    }
  ]
})

describe('Navigation', () => {
  it('navigates to tasks page', async () => {
    const { getByText } = render(Navigation, {
      global: {
        plugins: [router]
      }
    })
    
    await fireEvent.click(getByText('Zadania'))
    expect(router.currentRoute.value.path).toBe('/tasks')
  })
})
```

## Testy API

### Mock API

```javascript
import { rest } from 'msw'
import { setupServer } from 'msw/node'

const server = setupServer(
  rest.get('/api/tasks', (req, res, ctx) => {
    return res(
      ctx.json([
        { id: 1, title: 'Task 1' },
        { id: 2, title: 'Task 2' }
      ])
    )
  })
)

beforeAll(() => server.listen())
afterEach(() => server.resetHandlers())
afterAll(() => server.close())
```

## Testy Snapshot

```javascript
describe('TaskList', () => {
  it('matches snapshot', () => {
    const { container } = render(TaskList, {
      props: {
        tasks: [
          { id: 1, title: 'Task 1' },
          { id: 2, title: 'Task 2' }
        ]
      }
    })
    
    expect(container).toMatchSnapshot()
  })
})
```

## Testy Asynchroniczne

```javascript
describe('TaskList', () => {
  it('loads tasks asynchronously', async () => {
    render(TaskList)
    
    expect(screen.getByText('Ładowanie...')).toBeInTheDocument()
    
    await screen.findByText('Task 1')
    expect(screen.queryByText('Ładowanie...')).not.toBeInTheDocument()
  })
})
```

## Testy Integracyjne

```javascript
describe('Task Management', () => {
  it('creates and deletes task', async () => {
    render(TaskManager)
    
    // Tworzenie zadania
    await fireEvent.update(screen.getByLabelText('Tytuł'), 'New Task')
    await fireEvent.click(screen.getByText('Dodaj'))
    
    expect(screen.getByText('New Task')).toBeInTheDocument()
    
    // Usuwanie zadania
    await fireEvent.click(screen.getByText('Usuń'))
    expect(screen.queryByText('New Task')).not.toBeInTheDocument()
  })
})
```

## Best Practices

1. Testuj zachowanie, nie implementację
2. Używaj znaczących nazw testów
3. Grupuj powiązane testy
4. Używaj beforeEach i afterEach do czyszczenia
5. Mockuj zewnętrzne zależności
6. Testuj edge cases
7. Utrzymuj testy proste i czytelne
8. Używaj testów snapshot z rozwagą
9. Pisz testy przed implementacją (TDD)
10. Utrzymuj wysokie pokrycie kodu testami

## Debugowanie Testów

### Tryb Debug

```bash
docker-compose exec frontend npm run test -- --debug
```

### Logowanie

```javascript
describe('TaskList', () => {
  it('debug test', () => {
    console.log('Debug info')
    debugger
    // test code
  })
})
```

### Timeout

```javascript
it('long running test', async () => {
  // test code
}, 10000) // 10 sekund timeout
``` 