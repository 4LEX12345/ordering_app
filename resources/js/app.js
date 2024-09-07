import './bootstrap';
import { createApp } from 'vue';
import {ServerTable, ClientTable, EventBus} from 'v-tables-3';
import 'vue3-toastify/dist/index.css';
import DashboardComponent from './components/DashboardComponent.vue';
import RoleComponent from './components/masterdata/RoleComponent.vue';
import UserComponenrt from './components/system/UserComponent.vue';

// Create Vue application
const app = createApp({});
app.component('dashboard-component', DashboardComponent);
app.component('role-component', RoleComponent);
app.component('user-component', UserComponenrt);
app.use(ClientTable);
app.mount('#app');