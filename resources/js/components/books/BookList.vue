<template>
  <div class="book-list">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Books</h2>
      <button 
        @click="showForm = true; editingBook = null" 
        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
      >
        Add Book
      </button>
    </div>

    <div v-if="loading" class="text-center py-4">
      <p>Loading books...</p>
    </div>

    <div v-else-if="books.length === 0" class="text-center py-4">
      <p>No books found. Add your first book!</p>
    </div>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full bg-white">
        <thead>
          <tr>
            <th class="py-2 px-4 border-b text-left">Title</th>
            <th class="py-2 px-4 border-b text-left">Author</th>
            <th class="py-2 px-4 border-b text-center">Status</th>
            <th class="py-2 px-4 border-b text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="book in books" :key="book.id" class="hover:bg-gray-100">
            <td class="py-2 px-4 border-b">{{ book.title }}</td>
            <td class="py-2 px-4 border-b">{{ book.author.name }} {{ book.author.surname }}</td>
            <td class="py-2 px-4 border-b text-center">
              <span 
                :class="book.is_borrowed ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'" 
                class="px-2 py-1 rounded-full text-xs font-medium"
              >
                {{ book.is_borrowed ? 'Borrowed' : 'Available' }}
              </span>
              <button 
                @click="toggleBorrowedStatus(book)" 
                class="ml-2 px-2 py-1 text-xs rounded bg-gray-200 hover:bg-gray-300"
                :disabled="toggling === book.id"
              >
                {{ toggling === book.id ? '...' : 'Toggle' }}
              </button>
            </td>
            <td class="py-2 px-4 border-b text-center">
              <button 
                @click="editBook(book)" 
                class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2"
              >
                Edit
              </button>
              <button 
                @click="confirmDelete(book)" 
                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Book Form Modal -->
    <div v-if="showForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">{{ editingBook ? 'Edit Book' : 'Add Book' }}</h3>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Title</label>
          <input 
            v-model="formData.title" 
            type="text" 
            class="w-full px-3 py-2 border rounded"
            placeholder="Enter book title"
          >
          <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title[0] }}</p>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Author</label>
          <select 
            v-model="formData.author_id" 
            class="w-full px-3 py-2 border rounded"
          >
            <option value="" disabled>Select an author</option>
            <option v-for="author in authors" :key="author.id" :value="author.id">
              {{ author.name }} {{ author.surname }}
            </option>
          </select>
          <p v-if="errors.author_id" class="text-red-500 text-sm mt-1">{{ errors.author_id[0] }}</p>
        </div>

        <div class="mb-4">
          <label class="flex items-center">
            <input 
              v-model="formData.is_borrowed" 
              type="checkbox" 
              class="mr-2"
            >
            <span class="text-sm font-medium">Is Borrowed</span>
          </label>
        </div>

        <div class="flex justify-end space-x-2">
          <button 
            @click="showForm = false" 
            class="px-4 py-2 border rounded hover:bg-gray-100"
          >
            Cancel
          </button>
          <button 
            @click="saveBook" 
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            :disabled="saving"
          >
            {{ saving ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
        <p>Are you sure you want to delete "{{ bookToDelete?.title }}"?</p>

        <div class="flex justify-end space-x-2 mt-4">
          <button 
            @click="showDeleteConfirm = false" 
            class="px-4 py-2 border rounded hover:bg-gray-100"
          >
            Cancel
          </button>
          <button 
            @click="deleteBook" 
            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
            :disabled="deleting"
          >
            {{ deleting ? 'Deleting...' : 'Delete' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    indexUrl: {
      type: String,
      required: true
    },
    storeUrl: {
      type: String,
      required: true
    },
    showUrl: {
      type: String,
      required: true
    },
    updateUrl: {
      type: String,
      required: true
    },
    deleteUrl: {
      type: String,
      required: true
    },
    toggleBorrowedUrl: {
      type: String,
      required: true
    },
    authorsUrl: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      books: [],
      authors: [],
      loading: true,
      showForm: false,
      editingBook: null,
      formData: {
        title: '',
        author_id: '',
        is_borrowed: false
      },
      errors: {},
      saving: false,
      showDeleteConfirm: false,
      bookToDelete: null,
      deleting: false,
      toggling: null
    }
  },
  mounted() {
    this.fetchBooks();
    this.fetchAuthors();
  },
  methods: {
    fetchBooks() {
      this.loading = true;
      fetch(this.indexUrl)
        .then(response => response.json())
        .then(data => {
          this.books = data;
          this.loading = false;
        })
        .catch(error => {
          console.error('Error fetching books:', error);
          this.loading = false;
        });
    },
    fetchAuthors() {
      fetch(this.authorsUrl)
        .then(response => response.json())
        .then(data => {
          this.authors = data;
        })
        .catch(error => {
          console.error('Error fetching authors:', error);
        });
    },
    editBook(book) {
      this.editingBook = book;
      this.formData = {
        title: book.title,
        author_id: book.author_id,
        is_borrowed: book.is_borrowed
      };
      this.showForm = true;
    },
    saveBook() {
      this.saving = true;
      this.errors = {};

      const url = this.editingBook 
        ? this.updateUrl.replace('__id__', this.editingBook.id) 
        : this.storeUrl;

      const method = this.editingBook ? 'PUT' : 'POST';

      fetch(url, {
        method: method,
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(this.formData)
      })
        .then(response => {
          if (!response.ok) {
            return response.json().then(data => {
              if (data.errors) {
                this.errors = data.errors;
              }
              throw new Error('Failed to save book');
            });
          }
          return response.json();
        })
        .then(data => {
          this.showForm = false;
          this.fetchBooks();
          this.resetForm();
        })
        .catch(error => {
          console.error('Error saving book:', error);
        })
        .finally(() => {
          this.saving = false;
        });
    },
    confirmDelete(book) {
      this.bookToDelete = book;
      this.showDeleteConfirm = true;
    },
    deleteBook() {
      if (!this.bookToDelete) return;

      this.deleting = true;

      fetch(this.deleteUrl.replace('__id__', this.bookToDelete.id), {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Failed to delete book');
          }
          this.showDeleteConfirm = false;
          this.fetchBooks();
        })
        .catch(error => {
          console.error('Error deleting book:', error);
        })
        .finally(() => {
          this.deleting = false;
          this.bookToDelete = null;
        });
    },
    toggleBorrowedStatus(book) {
      this.toggling = book.id;

      fetch(this.toggleBorrowedUrl.replace('__id__', book.id), {
        method: 'PATCH',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Failed to toggle borrowed status');
          }
          return response.json();
        })
        .then(data => {
          // Update the book in the list
          const index = this.books.findIndex(b => b.id === book.id);
          if (index !== -1) {
            this.books[index].is_borrowed = data.is_borrowed;
          }
        })
        .catch(error => {
          console.error('Error toggling borrowed status:', error);
        })
        .finally(() => {
          this.toggling = null;
        });
    },
    resetForm() {
      this.formData = {
        title: '',
        author_id: '',
        is_borrowed: false
      };
      this.editingBook = null;
      this.errors = {};
    }
  }
}
</script>
