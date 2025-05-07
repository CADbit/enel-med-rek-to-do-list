<template>
  <Toast position="top-right" />
  <ConfirmDialog />
  <div v-if="error" class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full mx-4">
      <div class="flex items-center mb-4">
        <i class="pi pi-exclamation-triangle text-red-500 text-2xl mr-3"></i>
        <h2 class="text-red-600 text-xl font-bold">Error Loading Application</h2>
      </div>
      <p class="text-gray-700 mb-4">{{ error }}</p>
      <div class="flex justify-end">
        <Button
          label="Try Again"
          icon="pi pi-refresh"
          class="p-button-primary"
          @click="retryConnection"
        />
      </div>
    </div>
  </div>
  <div v-else class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">
          <i class="pi pi-tasks mr-2"></i>
          Task Manager
        </h1>
        
        <Alert
          :show="!!alertMessage"
          :type="alertType"
          :message="alertMessage"
          @close="clearAlert"
        />

        <div v-if="isBackendAvailable" class="space-y-4">
          <div v-if="loadingRequests.size > 0" class="fixed top-4 right-4 z-50">
            <div class="bg-white p-4 rounded-lg shadow-lg flex items-center">
              <i class="pi pi-spin pi-spinner text-blue-500 text-xl mr-2"></i>
              <span class="text-gray-700">Loading...</span>
            </div>
          </div>

          <Button
            v-if="!showForm"
            @click="showForm = true"
            icon="pi pi-plus"
            label="Add New Task"
            class="p-button-primary mb-4 w-full sm:w-auto"
          />

          <TaskForm
            v-if="showForm"
            :task="editingTask"
            :is-backend-available="isBackendAvailable"
            @submit="handleTaskSubmit"
            @cancel="cancelForm"
          />

          <TaskList
            :tasks="tasks"
            :total-records="totalRecords"
            :loading="loading"
            :sort-field="sortField"
            :sort-order="sortOrder"
            @edit="editTask"
            @delete="deleteTask"
            @toggle-status="toggleTaskStatus"
            @page="onPageChange"
            @sort="onSort"
          />
        </div>
        <div v-else class="text-center py-8">
          <i class="pi pi-exclamation-circle text-yellow-500 text-4xl mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-700 mb-2">Backend Service Unavailable</h3>
          <p class="text-gray-600 mb-4">Unable to connect to the backend service. Please check if the server is running.</p>
          <Button
            label="Retry Connection"
            icon="pi pi-refresh"
            class="p-button-primary"
            @click="checkBackendConnection"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import ConfirmDialog from 'primevue/confirmdialog';
import TaskList from './components/TaskList.vue';
import TaskForm from './components/TaskForm.vue';
import Alert from './components/Alert.vue';

const error = ref('');
const tasks = ref([]);
const showForm = ref(false);
const editingTask = ref({});
const alertMessage = ref('');
const alertType = ref('success');
const isBackendAvailable = ref(true);
const API_URL = 'http://localhost:8000/api';
const RESPONSE_TIME_WARNING_THRESHOLD = 500; // 500ms
const REQUEST_TIMEOUT = 5000; // 5 seconds
const loadingRequests = ref(new Set());
const totalRecords = ref(0);
const loading = ref(false);
const sortField = ref('created_at');
const sortOrder = ref(-1);

// Konfiguracja Axios
axios.defaults.timeout = REQUEST_TIMEOUT;

axios.interceptors.request.use(
  config => {
    // Dodajemy timestamp do konfiguracji
    config.metadata = { startTime: new Date().getTime() };
    // Dodajemy unikalny identyfikator zapytania
    config.metadata.requestId = Date.now();
    loadingRequests.value.add(config.metadata.requestId);
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

axios.interceptors.response.use(
  response => {
    // Usuwamy zapytanie z listy ładowania
    loadingRequests.value.delete(response.config.metadata.requestId);
    
    // Obliczamy czas odpowiedzi
    const endTime = new Date().getTime();
    const startTime = response.config.metadata.startTime;
    const responseTime = endTime - startTime;

    // Jeśli czas odpowiedzi przekracza próg, wyświetlamy ostrzeżenie
    if (responseTime > RESPONSE_TIME_WARNING_THRESHOLD) {
      showAlert(`Slow response detected (${responseTime}ms). The server might be overloaded.`, 'warn');
    }

    isBackendAvailable.value = true;
    return response;
  },
  error => {
    // Usuwamy zapytanie z listy ładowania w przypadku błędu
    if (error.config?.metadata?.requestId) {
      loadingRequests.value.delete(error.config.metadata.requestId);
    }

    if (error.code === 'ECONNABORTED') {
      showAlert('Request timeout. The server is taking too long to respond.', 'error');
      isBackendAvailable.value = false;
    } else if (error.code === 'ERR_NETWORK') {
      isBackendAvailable.value = false;
      showAlert('Cannot connect to the backend server. Please make sure it is running on http://localhost:8000', 'error');
    } else if (error.response) {
      // Obsługa różnych kodów odpowiedzi HTTP
      switch (error.response.status) {
        case 401:
          showAlert('Unauthorized access. Please check your credentials.', 'error');
          break;
        case 403:
          showAlert('Access forbidden. You don\'t have permission to perform this action.', 'error');
          break;
        case 404:
          showAlert('Resource not found. The requested data does not exist.', 'error');
          break;
        case 422:
          showAlert(error.response.data?.message || 'Validation error. Please check your input.', 'error');
          break;
        case 500:
          showAlert('Server error. Please try again later.', 'error');
          break;
        default:
          showAlert(error.response.data?.message || `Error: ${error.response.status}`, 'error');
      }
    } else {
      showAlert('Error connecting to the backend service', 'error');
    }
    return Promise.reject(error);
  }
);

const checkBackendConnection = async () => {
  try {
    const response = await axios.get(`${API_URL}/tasks/statuses`);
    // Sprawdzamy czy odpowiedź jest poprawna
    if (response.status >= 200 && response.status < 300) {
      isBackendAvailable.value = true;
      return true;
    }
    return false;
  } catch (error) {
    isBackendAvailable.value = false;
    return false;
  }
};

const retryConnection = async () => {
  error.value = '';
  try {
    const isConnected = await checkBackendConnection();
    if (isConnected) {
      await fetchTasks();
      showAlert('Connection restored successfully', 'success');
    } else {
      showAlert('Failed to connect to the backend server', 'error');
    }
  } catch (error) {
    console.error('Error during retry:', error);
    showAlert('Error during connection retry', 'error');
  }
};

const fetchTasks = async (page = 1, rows = 10) => {
  loading.value = true;
  try {
    const response = await axios.get(`${API_URL}/tasks`, {
      params: {
        page,
        per_page: rows,
        sort_field: sortField.value,
        sort_order: sortOrder.value === 1 ? 'asc' : 'desc'
      }
    });
    // Sprawdzamy czy odpowiedź jest poprawna
    if (response.status >= 200 && response.status < 300) {
      tasks.value = response.data.data.data;
      totalRecords.value = response.data.meta.total;
    } else {
      throw new Error('Unexpected response from server');
    }
  } catch (error) {
    console.error('Error fetching tasks:', error);
    if (error.code === 'ERR_NETWORK') {
      error.value = 'Cannot connect to the backend server. Please make sure it is running on http://localhost:8000';
    } else {
      error.value = 'Error loading tasks: ' + (error.response?.data?.message || error.message);
    }
  } finally {
    loading.value = false;
  }
};

const handleTaskSubmit = async (taskData) => {
  try {
    let response;
    if (editingTask.value.id) {
      response = await axios.put(`${API_URL}/tasks/${editingTask.value.id}`, taskData);
    } else {
      response = await axios.post(`${API_URL}/tasks`, taskData);
    }
    
    // Sprawdzamy czy odpowiedź jest poprawna
    if (response.status >= 200 && response.status < 300) {
      showAlert(editingTask.value.id ? 'Task updated successfully' : 'Task created successfully', 'success');
      await fetchTasks();
      cancelForm();
    } else {
      throw new Error('Unexpected response from server');
    }
  } catch (error) {
    console.error('Error saving task:', error);
  }
};

const editTask = (task) => {
  editingTask.value = { ...task };
  showForm.value = true;
};

const deleteTask = async (taskId) => {
  try {
    const response = await axios.delete(`${API_URL}/tasks/${taskId}`);
    // Sprawdzamy czy odpowiedź jest poprawna
    if (response.status >= 200 && response.status < 300) {
      showAlert('Task deleted successfully', 'success');
      await fetchTasks();
    } else {
      throw new Error('Unexpected response from server');
    }
  } catch (error) {
    console.error('Error deleting task:', error);
  }
};

const toggleTaskStatus = async (task) => {
  try {
    const newStatus = task.status === 'completed' ? 'pending' : 'completed';
    const response = await axios.put(`${API_URL}/tasks/${task.id}`, { ...task, status: newStatus });
    // Sprawdzamy czy odpowiedź jest poprawna
    if (response.status >= 200 && response.status < 300) {
      await fetchTasks();
    } else {
      throw new Error('Unexpected response from server');
    }
  } catch (error) {
    console.error('Error updating task status:', error);
  }
};

const cancelForm = () => {
  showForm.value = false;
  editingTask.value = {};
};

const showAlert = (message, type = 'success') => {
  alertMessage.value = message;
  alertType.value = type;
  // Force the alert to show by setting show to true
  setTimeout(() => {
    alertMessage.value = '';
  }, 100);
};

const clearAlert = () => {
  alertMessage.value = '';
};

// Dodajemy watch na isBackendAvailable
watch(isBackendAvailable, async (newValue) => {
  if (newValue) {
    await fetchTasks();
  }
});

// Dodajemy nową funkcję do obsługi zmiany strony
const onPageChange = (event) => {
  fetchTasks(event.page + 1, event.rows);
};

// Dodajemy nową funkcję do obsługi zmiany liczby wierszy na stronie
const onRowsChange = (event) => {
  fetchTasks(1, event.rows);
};

// Dodajemy nową funkcję do obsługi sortowania
const onSort = (event) => {
  sortField.value = event.sortField;
  sortOrder.value = event.sortOrder;
  fetchTasks(1, 10); // Resetujemy do pierwszej strony przy sortowaniu
};

onMounted(async () => {
  try {
    await checkBackendConnection();
    if (isBackendAvailable.value) {
      await fetchTasks();
    }
  } catch (e) {
    error.value = 'Error initializing application: ' + e.message;
  }
});
</script>

<style>
.p-button {
  font-weight: 500;
}

.p-button .p-button-icon {
  font-size: 1rem;
  color: white;
}

.p-button.p-button-primary {
  background: #60A5FA;
  padding: 0.75rem 1.5rem;
  color: white;
}

.p-button.p-button-primary:hover {
  background: #3B82F6;
  color: white;
}

/* Dodatkowe style dla PrimeVue */
:deep(.p-component) {
  font-family: inherit;
}

:deep(.p-datatable) {
  font-size: 0.875rem;
}

:deep(.p-button) {
  font-size: 0.875rem;
}

:deep(.p-inputtext) {
  font-size: 0.875rem;
}

:deep(.p-dropdown) {
  font-size: 0.875rem;
}
</style> 