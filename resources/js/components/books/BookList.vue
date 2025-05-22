<template>
  <div class="book-list">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Books</h2>
      <button 
        v-if="isAdmin"
        @click="addNewBook" 
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
            <th v-if="isAdmin" class="py-2 px-4 border-b text-center">Actions</th>
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
            <td v-if="isAdmin" class="py-2 px-4 border-b text-center">
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
    <div class="modal fade" id="bookFormModal" tabindex="-1" aria-labelledby="bookFormModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="bookFormModalLabel">{{ editingBook ? 'Edit Book' : 'Add Book' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="bookTitle" class="form-label">Title</label>
              <input 
                v-model="formData.title" 
                type="text" 
                class="form-control"
                id="bookTitle"
                placeholder="Enter book title"
              >
              <div v-if="errors.title" class="text-danger mt-1">{{ errors.title[0] }}</div>
            </div>

            <div class="mb-3">
              <label for="bookAuthor" class="form-label">Author</label>
              <select 
                v-model="formData.author_id" 
                class="form-select"
                id="bookAuthor"
              >
                <option value="" disabled>Select an author</option>
                <option v-for="author in authors" :key="author.id" :value="author.id">
                  {{ author.name }} {{ author.surname }}
                </option>
              </select>
              <div v-if="errors.author_id" class="text-danger mt-1">{{ errors.author_id[0] }}</div>
            </div>

            <div class="mb-3 form-check">
              <input 
                v-model="formData.is_borrowed" 
                type="checkbox" 
                class="form-check-input"
                id="bookBorrowed"
              >
              <label class="form-check-label" for="bookBorrowed">Is Borrowed</label>
            </div>
          </div>
          <div class="modal-footer">
            <button 
              type="button" 
              class="btn btn-secondary" 
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button 
              type="button"
              @click="saveBook" 
              class="btn btn-primary"
              :disabled="saving"
            >
              {{ saving ? 'Saving...' : 'Save' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete "{{ bookToDelete?.title }}"?</p>
          </div>
          <div class="modal-footer">
            <button 
              type="button" 
              class="btn btn-secondary" 
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button 
              type="button"
              @click="deleteBook" 
              class="btn btn-danger"
              :disabled="deleting"
            >
              {{ deleting ? 'Deleting...' : 'Delete' }}
            </button>
          </div>
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
    },
    isAdmin: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      books: [],
      authors: [],
      loading: true,
      editingBook: null,
      formData: {
        title: '',
        author_id: '',
        is_borrowed: false
      },
      errors: {},
      saving: false,
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
        .then(response => {
          // Check if the response is JSON by looking at the content-type header
          const contentType = response.headers.get('content-type');
          if (contentType && contentType.includes('application/json')) {
            return response.json();
          } else {
            // If not JSON, just return an empty array
            console.error('Error fetching books: Server returned a non-JSON response');
            return [];
          }
        })
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
        .then(response => {
          // Check if the response is JSON by looking at the content-type header
          const contentType = response.headers.get('content-type');
          if (contentType && contentType.includes('application/json')) {
            return response.json();
          } else {
            // If not JSON, just return an empty array
            console.error('Error fetching authors: Server returned a non-JSON response');
            return [];
          }
        })
        .then(data => {
          this.authors = data;
        })
        .catch(error => {
          console.error('Error fetching authors:', error);
        });
    },
    addNewBook() {
      this.editingBook = null;
      this.resetForm();
      this.showBookModal();
    },
    editBook(book) {
      this.editingBook = book;
      this.formData = {
        title: book.title,
        author_id: book.author_id,
        is_borrowed: book.is_borrowed
      };
      this.showBookModal();
    },
    showBookModal() {
      const bookFormModal = new bootstrap.Modal(document.getElementById('bookFormModal'));
      bookFormModal.show();
    },
    hideBookModal() {
      const modalElement = document.getElementById('bookFormModal');
      const bookFormModal = bootstrap.Modal.getInstance(modalElement);
      if (bookFormModal) {
        bookFormModal.hide();
      }
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
            // Check if the response is JSON by looking at the content-type header
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
              return response.json().then(data => {
                if (data.errors) {
                  this.errors = data.errors;
                }
                throw new Error('Failed to save book');
              });
            } else {
              // If not JSON, just throw a generic error
              throw new Error('Failed to save book: Server returned a non-JSON response');
            }
          }
          return response.json();
        })
        .then(data => {
          this.hideBookModal();
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
      this.showDeleteModal();
    },
    showDeleteModal() {
      const deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
      deleteConfirmModal.show();
    },
    hideDeleteModal() {
      const modalElement = document.getElementById('deleteConfirmModal');
      const deleteConfirmModal = bootstrap.Modal.getInstance(modalElement);
      if (deleteConfirmModal) {
        deleteConfirmModal.hide();
      }
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
            // Check if the response is JSON by looking at the content-type header
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
              return response.json().then(data => {
                throw new Error(data.message || 'Failed to delete book');
              });
            } else {
              // If not JSON, just throw a generic error
              throw new Error('Failed to delete book: Server returned a non-JSON response');
            }
          }
          this.hideDeleteModal();
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
            // Check if the response is JSON by looking at the content-type header
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
              return response.json().then(data => {
                throw new Error(data.message || 'Failed to toggle borrowed status');
              });
            } else {
              // If not JSON, just throw a generic error
              throw new Error('Failed to toggle borrowed status: Server returned a non-JSON response');
            }
          }
          // Check if the response is JSON by looking at the content-type header
          const contentType = response.headers.get('content-type');
          if (contentType && contentType.includes('application/json')) {
            return response.json();
          } else {
            // If not JSON, just return an empty object
            return {};
          }
        })
        .then(data => {
          // Update the book in the list
          const index = this.books.findIndex(b => b.id === book.id);
          if (index !== -1 && data.is_borrowed !== undefined) {
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
