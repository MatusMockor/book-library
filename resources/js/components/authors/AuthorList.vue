<template>
  <div class="author-list">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Authors</h2>
      <button 
        @click="showForm = true; editingAuthor = null" 
        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
      >
        Add Author
      </button>
    </div>

    <div v-if="loading" class="text-center py-4">
      <p>Loading authors...</p>
    </div>

    <div v-else-if="authors.length === 0" class="text-center py-4">
      <p>No authors found. Add your first author!</p>
    </div>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full bg-white">
        <thead>
          <tr>
            <th class="py-2 px-4 border-b text-left">Name</th>
            <th class="py-2 px-4 border-b text-left">Surname</th>
            <th class="py-2 px-4 border-b text-center">Books Count</th>
            <th class="py-2 px-4 border-b text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="author in authors" :key="author.id" class="hover:bg-gray-100">
            <td class="py-2 px-4 border-b">{{ author.name }}</td>
            <td class="py-2 px-4 border-b">{{ author.surname }}</td>
            <td class="py-2 px-4 border-b text-center">{{ author.book_count }}</td>
            <td class="py-2 px-4 border-b text-center">
              <button 
                @click="editAuthor(author)" 
                class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2"
              >
                Edit
              </button>
              <button 
                @click="confirmDelete(author)" 
                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Author Form Modal -->
    <div v-if="showForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">{{ editingAuthor ? 'Edit Author' : 'Add Author' }}</h3>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">First Name</label>
          <input 
            v-model="formData.name" 
            type="text" 
            class="w-full px-3 py-2 border rounded"
            placeholder="Enter first name"
          >
          <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name[0] }}</p>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Last Name</label>
          <input 
            v-model="formData.surname" 
            type="text" 
            class="w-full px-3 py-2 border rounded"
            placeholder="Enter last name"
          >
          <p v-if="errors.surname" class="text-red-500 text-sm mt-1">{{ errors.surname[0] }}</p>
        </div>

        <div class="flex justify-end space-x-2">
          <button 
            @click="showForm = false" 
            class="px-4 py-2 border rounded hover:bg-gray-100"
          >
            Cancel
          </button>
          <button 
            @click="saveAuthor" 
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
        <p>Are you sure you want to delete {{ authorToDelete?.name }} {{ authorToDelete?.surname }}?</p>
        <p class="text-red-500 text-sm mt-2">This will also delete all books associated with this author.</p>

        <div class="flex justify-end space-x-2 mt-4">
          <button 
            @click="showDeleteConfirm = false" 
            class="px-4 py-2 border rounded hover:bg-gray-100"
          >
            Cancel
          </button>
          <button 
            @click="deleteAuthor" 
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
  data() {
    return {
      authors: [],
      loading: true,
      showForm: false,
      editingAuthor: null,
      formData: {
        name: '',
        surname: ''
      },
      errors: {},
      saving: false,
      showDeleteConfirm: false,
      authorToDelete: null,
      deleting: false
    }
  },
  mounted() {
    this.fetchAuthors();
  },
  methods: {
    fetchAuthors() {
      this.loading = true;
      fetch('/authors/api')
        .then(response => response.json())
        .then(data => {
          this.authors = data;
          this.loading = false;
        })
        .catch(error => {
          console.error('Error fetching authors:', error);
          this.loading = false;
        });
    },
    editAuthor(author) {
      this.editingAuthor = author;
      this.formData = {
        name: author.name,
        surname: author.surname
      };
      this.showForm = true;
    },
    saveAuthor() {
      this.saving = true;
      this.errors = {};

      const url = this.editingAuthor 
        ? `/authors/${this.editingAuthor.id}` 
        : '/authors';

      const method = this.editingAuthor ? 'PUT' : 'POST';

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
              throw new Error('Failed to save author');
            });
          }
          return response.json();
        })
        .then(data => {
          this.showForm = false;
          this.fetchAuthors();
          this.resetForm();
        })
        .catch(error => {
          console.error('Error saving author:', error);
        })
        .finally(() => {
          this.saving = false;
        });
    },
    confirmDelete(author) {
      this.authorToDelete = author;
      this.showDeleteConfirm = true;
    },
    deleteAuthor() {
      if (!this.authorToDelete) return;

      this.deleting = true;

      fetch(`/authors/${this.authorToDelete.id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Failed to delete author');
          }
          this.showDeleteConfirm = false;
          this.fetchAuthors();
        })
        .catch(error => {
          console.error('Error deleting author:', error);
        })
        .finally(() => {
          this.deleting = false;
          this.authorToDelete = null;
        });
    },
    resetForm() {
      this.formData = {
        name: '',
        surname: ''
      };
      this.editingAuthor = null;
      this.errors = {};
    }
  }
}
</script>
