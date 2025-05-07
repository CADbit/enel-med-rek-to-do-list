<template>
  <div class="mt-8">
    <Card class="shadow-2">
      <template #title>
        <div class="flex align-items-center">
          <i class="pi pi-tasks mr-2"></i>
          {{ isEditing ? 'Edit Task' : 'Create New Task' }}
        </div>
      </template>
      <template #content>
        <div v-if="!isBackendAvailable" class="p-4 bg-red-50 border border-red-200 rounded-lg mb-4">
          <div class="flex items-center">
            <i class="pi pi-exclamation-triangle text-red-500 mr-2"></i>
            <span class="text-red-700">Backend service is not available. Please try again later.</span>
          </div>
        </div>
        <form @submit.prevent="handleSubmit" class="p-fluid">
          <div class="field">
            <label for="title" class="font-bold">Title</label>
            <InputText
              id="title"
              v-model="form.title"
              :class="{ 'p-invalid': errors.title }"
              placeholder="Enter task title"
              :disabled="!isBackendAvailable"
            />
            <small v-if="errors.title" class="p-error">{{ errors.title }}</small>
          </div>

          <div class="field">
            <label for="description" class="font-bold">Description</label>
            <Textarea
              id="description"
              v-model="form.description"
              rows="3"
              placeholder="Enter task description"
              :disabled="!isBackendAvailable"
            />
          </div>

          <div class="field">
            <label for="status" class="font-bold">Status</label>
            <Dropdown
              id="status"
              v-model="form.status"
              :options="statusOptions"
              optionLabel="label"
              optionValue="value"
              placeholder="Select status"
              class="w-full"
              :class="{ 'p-invalid': errors.status || statusError }"
              :disabled="!isBackendAvailable"
            />
            <small v-if="errors.status" class="p-error">{{ errors.status }}</small>
            <small v-if="statusError" class="p-error">{{ statusError }}</small>
          </div>

          <div class="flex mt-4">
            <div class="w-1/2"></div>
            <div class="w-1/2 flex justify-content-end gap-2">
              <Button
                type="button"
                label="Cancel"
                icon="pi pi-times"
                class="p-button-danger"
                @click="$emit('cancel')"
              />
              <Button
                type="submit"
                :label="isEditing ? 'Update Task' : 'Create Task'"
                icon="pi pi-check"
                class="p-button-success"
                :loading="loading"
                :disabled="!isBackendAvailable"
              />
            </div>
          </div>
        </form>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import axios from 'axios';

const props = defineProps({
  task: {
    type: Object,
    default: () => ({
      title: '',
      description: '',
      status: 'new'
    })
  },
  isBackendAvailable: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits(['submit', 'cancel']);

const form = ref({
  title: props.task.title,
  description: props.task.description,
  status: props.task.status || 'new'
});

const errors = ref({});
const loading = ref(false);
const statusOptions = ref([]);
const statusError = ref('');

const API_URL = 'http://localhost:8000/api';

const fetchStatuses = async () => {
  if (!props.isBackendAvailable) {
    statusError.value = 'Backend service is not available';
    return;
  }

  try {
    console.log('Fetching statuses...');
    const response = await axios.get(`${API_URL}/tasks/statuses`);
    console.log('Statuses response:', response.data);
    if (response.status >= 200 && response.status < 300) {
      statusOptions.value = response.data;
      statusError.value = '';
    } else {
      throw new Error('Unexpected response from server');
    }
  } catch (error) {
    console.error('Error fetching statuses:', error);
    statusError.value = 'Failed to load statuses. Please try again.';
  }
};

// Watch for changes in backend availability
watch(() => props.isBackendAvailable, (newValue) => {
  if (newValue) {
    fetchStatuses();
  } else {
    statusError.value = 'Backend service is not available';
  }
});

onMounted(() => {
  if (props.isBackendAvailable) {
    fetchStatuses();
  } else {
    statusError.value = 'Backend service is not available';
  }
});

const isEditing = computed(() => !!props.task.id);

const validateForm = () => {
  errors.value = {};
  if (!props.isBackendAvailable) {
    errors.value.backend = 'Backend service is not available';
    return false;
  }
  if (!form.value.title.trim()) {
    errors.value.title = 'Title is required';
  }
  if (!form.value.status) {
    errors.value.status = 'Status is required';
  }
  return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
  if (validateForm()) {
    loading.value = true;
    try {
      emit('submit', { ...form.value });
    } finally {
      loading.value = false;
    }
  }
};
</script>

<style scoped>
:deep(.p-card) {
  border-radius: 0.5rem;
}

:deep(.p-card .p-card-title) {
  font-size: 1.25rem;
  color: #1e293b;
}

:deep(.p-card .p-card-content) {
  padding: 1.5rem;
}

:deep(.field) {
  margin-bottom: 1.5rem;
}

:deep(.p-inputtext),
:deep(.p-dropdown),
:deep(.p-textarea) {
  width: 100%;
}

:deep(.p-button) {
  min-width: 8rem;
  padding: 0.75rem 1.5rem;
  transition: background-color 0.2s;
  color: white;
}

:deep(.p-button.p-button-danger) {
  background: #EF4444;
}

:deep(.p-button.p-button-danger:hover) {
  background: #DC2626;
}

:deep(.p-button.p-button-success) {
  background: #10B981;
}

:deep(.p-button.p-button-success:hover) {
  background: #059669;
}

:deep(.p-button .p-button-icon) {
  color: white;
}
</style> 