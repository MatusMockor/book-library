<template>
  <div class="author-list">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Authors</h2>
      <button 
        v-if="isAdmin"
        @click="addNewAuthor" 
        class="px-4 py-2 bg-primary text-white border-0 rounded-0"
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
            <th v-if="isAdmin" class="py-2 px-4 border-b text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="author in authors" :key="author.id" class="hover:bg-gray-100">
            <td class="py-2 px-4 border-b">{{ author.name }}</td>
            <td class="py-2 px-4 border-b">{{ author.surname }}</td>
            <td class="py-2 px-4 border-b text-center">{{ author.book_count }}</td>
            <td class="py-2 px-4 border-b text-center">
              <button 
                v-if="isAdmin"
                @click="editAuthor(author)" 
                class="px-3 py-1 bg-info text-white border-0 rounded-0 mr-2"
              >
                Edit
              </button>
              <button 
                v-if="isAdmin"
                @click="confirmDelete(author)" 
                class="px-3 py-1 bg-danger text-white border-0 rounded-0"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Author Form Modal -->
    <div class="modal fade" id="authorFormModal" tabindex="-1" aria-labelledby="authorFormModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="authorFormModalLabel">{{ editingAuthor ? 'Edit Author' : 'Add Author' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="authorName" class="form-label">First Name</label>
              <input 
                v-model="formData.name" 
                type="text" 
                class="form-control"
                id="authorName"
                placeholder="Enter first name"
              >
              <div v-if="errors.name" class="text-danger mt-1">{{ errors.name[0] }}</div>
            </div>

            <div class="mb-3">
              <label for="authorSurname" class="form-label">Last Name</label>
              <input 
                v-model="formData.surname" 
                type="text" 
                class="form-control"
                id="authorSurname"
                placeholder="Enter last name"
              >
              <div v-if="errors.surname" class="text-danger mt-1">{{ errors.surname[0] }}</div>
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
              @click="saveAuthor" 
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
            <p>Are you sure you want to delete "{{ authorToDelete?.name }} {{ authorToDelete?.surname }}"?</p>
            <p class="text-danger mt-2">This will also delete all books associated with this author.</p>
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
              @click="deleteAuthor" 
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
    isAdmin: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      authors: [],
      loading: true,
      editingAuthor: null,
      formData: {
        name: '',
        surname: ''
      },
      errors: {},
      saving: false,
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
      fetch(this.indexUrl)
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
    addNewAuthor() {
      this.editingAuthor = null;
      this.resetForm();
      this.showAuthorModal();
    },
    editAuthor(author) {
      this.editingAuthor = author;
      this.formData = {
        name: author.name,
        surname: author.surname
      };
      this.showAuthorModal();
    },
    showAuthorModal() {
      const authorFormModal = new bootstrap.Modal(document.getElementById('authorFormModal'));
      authorFormModal.show();
    },
    hideAuthorModal() {
      const modalElement = document.getElementById('authorFormModal');
      const authorFormModal = bootstrap.Modal.getInstance(modalElement);
      if (authorFormModal) {
        authorFormModal.hide();
      }
    },
    saveAuthor() {
      this.saving = true;
      this.errors = {};

      const url = this.editingAuthor 
        ? this.updateUrl.replace('__id__', this.editingAuthor.id) 
        : this.storeUrl;

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
          this.hideAuthorModal();
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
    deleteAuthor() {
      if (!this.authorToDelete) return;

      this.deleting = true;

      fetch(this.deleteUrl.replace('__id__', this.authorToDelete.id), {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Failed to delete author');
          }
          this.hideDeleteModal();
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
