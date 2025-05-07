<template>
  <div class="mt-8">
    <DataTable
      :value="tasks"
      :paginator="true"
      :rows="10"
      :rowsPerPageOptions="[10, 20, 50]"
      :totalRecords="totalRecords"
      :lazy="true"
      :loading="loading"
      :sortField="sortField"
      :sortOrder="sortOrder"
      tableStyle="min-width: 50rem"
      class="p-datatable-sm"
      stripedRows
      showGridlines
      responsiveLayout="scroll"
      @page="onPage($event)"
      @sort="onSort($event)"
    >
      <template #loading>
        <div class="flex items-center justify-center p-4">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
          <span class="ml-2 text-gray-600">Loading...</span>
        </div>
      </template>
      <Column field="title" header="Title" sortable>
        <template #body="{ data }">
          <span class="font-medium">{{ data.title }}</span>
        </template>
      </Column>
      
      <Column field="description" header="Description">
        <template #body="{ data }">
          <span class="text-gray-600">{{ data.description }}</span>
        </template>
      </Column>
      
      <Column field="status" header="Status" sortable>
        <template #body="{ data }">
          <Tag
            :value="data.status"
            :severity="getStatusSeverity(data.status_color)"
          />
        </template>
      </Column>
      
      <Column header="Actions" style="width: 200px">
        <template #body="{ data }">
          <div class="flex gap-2">
            <Button
              icon="pi pi-pencil"
              @click="$emit('edit', data)"
              class="p-button-rounded p-button-text p-button-sm edit-button"
              v-tooltip.top="'Edit Task'"
            />
            <Button
              icon="pi pi-trash"
              @click="$emit('delete', data.id)"
              class="p-button-rounded p-button-text p-button-sm delete-button"
              v-tooltip.top="'Delete Task'"
            />
            <Button
              :icon="data.status === 'completed' ? 'pi pi-clock' : 'pi pi-check'"
              @click="$emit('toggle-status', data)"
              :class="[
                'p-button-rounded p-button-text p-button-sm',
                data.status === 'completed' ? 'status-pending-button' : 'status-complete-button'
              ]"
              v-tooltip.top="data.status === 'completed' ? 'Mark as Pending' : 'Mark as Completed'"
            />
          </div>
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { watch } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Tooltip from 'primevue/tooltip';

const props = defineProps({
  tasks: {
    type: Array,
    required: true
  },
  totalRecords: {
    type: Number,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  sortField: {
    type: String,
    default: null
  },
  sortOrder: {
    type: Number,
    default: null
  }
});

const emit = defineEmits(['edit', 'delete', 'toggle-status', 'page', 'sort']);

const onPage = (event) => {
  emit('page', event);
};

const onSort = (event) => {
  emit('sort', event);
};

const getStatusSeverity = (color) => {
  const severityMap = {
    'blue': 'info',
    'yellow': 'warning',
    'orange': 'warning',
    'red': 'danger',
    'green': 'success'
  };
  return severityMap[color] || 'info';
};
</script>

<style scoped>
:deep(.p-datatable) {
  border-radius: 0.5rem;
  overflow: hidden;
}

:deep(.p-datatable .p-datatable-thead > tr > th) {
  background: #f8fafc;
  color: #1e293b;
  font-weight: 600;
}

:deep(.p-datatable .p-datatable-tbody > tr) {
  transition: background-color 0.2s;
}

:deep(.p-datatable .p-datatable-tbody > tr:hover) {
  background: #f1f5f9 !important;
}

:deep(.p-button.p-button-text) {
  width: 2.5rem;
  height: 2.5rem;
}

:deep(.p-button.p-button-text .p-button-icon) {
  color: inherit !important;
}

:deep(.p-tag) {
  font-weight: 500;
}

:deep(.edit-button) {
  color: #3B82F6;
}

:deep(.edit-button:hover) {
  background: #EFF6FF;
}

:deep(.delete-button) {
  color: #EF4444;
}

:deep(.delete-button:hover) {
  background: #FEE2E2;
}

:deep(.status-complete-button) {
  color: #10B981;
}

:deep(.status-complete-button:hover) {
  background: #ECFDF5;
}

:deep(.status-pending-button) {
  color: #F59E0B;
}

:deep(.status-pending-button:hover) {
  background: #FFFBEB;
}
</style> 