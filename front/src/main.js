import { createApp } from 'vue'
import App from './App.vue'
import './style.css'

// PrimeVue
import PrimeVue from 'primevue/config'
import ToastService from 'primevue/toastservice'
import ConfirmationService from 'primevue/confirmationservice'
import 'primevue/resources/themes/lara-light-blue/theme.css'
import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'

// PrimeVue Components
import Button from 'primevue/button'
import Card from 'primevue/card'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Dropdown from 'primevue/dropdown'
import Toast from 'primevue/toast'
import ConfirmDialog from 'primevue/confirmdialog'
import Tag from 'primevue/tag'
import Tooltip from 'primevue/tooltip'

const app = createApp(App)

// Register PrimeVue with ripple effect
app.use(PrimeVue, { 
    ripple: true,
    unstyled: false,
    pt: {
        button: {
            root: { class: 'p-button' }
        }
    }
})

// Register PrimeVue Services
app.use(ToastService)
app.use(ConfirmationService)

// Register PrimeVue Components
app.component('Button', Button)
app.component('Card', Card)
app.component('DataTable', DataTable)
app.component('Column', Column)
app.component('InputText', InputText)
app.component('Textarea', Textarea)
app.component('Dropdown', Dropdown)
app.component('Toast', Toast)
app.component('ConfirmDialog', ConfirmDialog)
app.component('Tag', Tag)
app.directive('tooltip', Tooltip)

app.mount('#app') 