import './bootstrap';
import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';


// Initialize Vue
const app = createApp({});
app.component('example-component', ExampleComponent);
app.mount('#app');
