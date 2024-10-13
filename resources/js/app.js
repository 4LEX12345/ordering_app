import './bootstrap';
import { createApp } from 'vue';
import {ServerTable, ClientTable, EventBus} from 'v-tables-3';
import 'vue3-toastify/dist/index.css';
import DashboardComponent from './components/DashboardComponent.vue';
import RoleComponent from './components/masterdata/RoleComponent.vue';
import UserComponenrt from './components/system/UserComponent.vue';
import BrandComponent from './components/masterdata/BrandComponent.vue';
import FlavorComponent from './components/masterdata/FlavorComponent.vue';
import ProductCategoryComponent from './components/masterdata/ProductCategoryComponent.vue';
import ProductComponent from './components/product/ProductComponent.vue';
import OrderComponent from './components/order/OrderComponent.vue';

// Create Vue application
const app = createApp({});
app.component('dashboard-component', DashboardComponent);
app.component('role-component', RoleComponent);
app.component('user-component', UserComponenrt);
app.component('brand-component', BrandComponent);
app.component('flavor-component', FlavorComponent);
app.component('product-category-component', ProductCategoryComponent);
app.component('product-component', ProductComponent);
app.component('order-component', OrderComponent);

app.use(ClientTable);
app.mount('#app');