import './bootstrap';
import { createApp } from 'vue';
import AuthorList from './components/authors/AuthorList.vue';
import BookList from './components/books/BookList.vue';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

// Initialize Vue
const app = createApp({});

// Register components
app.component('author-list', AuthorList);
app.component('book-list', BookList);

app.mount('#app');
