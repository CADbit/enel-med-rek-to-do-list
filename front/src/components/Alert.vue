<template>
  <Toast position="top-right" />
  <ConfirmDialog />
</template>

<script setup>
import { watch } from 'vue';
import Toast from 'primevue/toast';
import ConfirmDialog from 'primevue/confirmdialog';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';

const toast = useToast();
const confirm = useConfirm();

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  type: {
    type: String,
    default: 'success',
    validator: (value) => ['success', 'error', 'info', 'warn'].includes(value)
  },
  message: {
    type: String,
    required: true
  }
});

const emit = defineEmits(['close']);

watch(() => props.show, (newValue) => {
  if (newValue && props.message) {
    toast.add({
      severity: props.type === 'warn' ? 'warn' : props.type,
      summary: props.type === 'error' ? 'Error' : 
              props.type === 'warn' ? 'Warning' : 
              props.type === 'info' ? 'Info' : 'Success',
      detail: props.message,
      life: 5000,
      closable: true
    });
    emit('close');
  }
}, { immediate: true });
</script>

<style scoped>
:deep(.p-toast) {
  z-index: 1000;
}

:deep(.p-toast .p-toast-message) {
  margin: 0 0 1rem 0;
  box-shadow: 0 2px 4px -1px rgba(0,0,0,.2), 0 4px 5px 0 rgba(0,0,0,.14), 0 1px 10px 0 rgba(0,0,0,.12);
  border-radius: 6px;
}

:deep(.p-toast .p-toast-message.p-toast-message-info) {
  background: #E3F2FD;
  border: solid #2196F3;
  border-width: 0 0 0 6px;
  color: #0D47A1;
}

:deep(.p-toast .p-toast-message.p-toast-message-success) {
  background: #E8F5E9;
  border: solid #4CAF50;
  border-width: 0 0 0 6px;
  color: #1B5E20;
}

:deep(.p-toast .p-toast-message.p-toast-message-warn) {
  background: #FFF3E0;
  border: solid #FFC107;
  border-width: 0 0 0 6px;
  color: #E65100;
}

:deep(.p-toast .p-toast-message.p-toast-message-error) {
  background: #FFEBEE;
  border: solid #F44336;
  border-width: 0 0 0 6px;
  color: #B71C1C;
}
</style> 