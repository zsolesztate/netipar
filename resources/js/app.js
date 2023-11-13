import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';

// Import components
import App from './components/App.vue';
import ContactsList from './components/ContactsList.vue';
import NewContact from './components/NewContact.vue';

import '../css/app.css';
//import Product from './components/Product.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: ContactsList },
        { path: '/addcontact', component: NewContact },
       // { path: '/products/:id', component: Product },
       // { path: '/products/:id/edit', component: ProductForm },
    ]
});

const app = createApp(App);
app.use(router);
app.mount('#app');