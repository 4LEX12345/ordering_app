<template>
    <div>
      <table>
        <thead>
          <tr>
            <th v-for="column in columns" :key="column.key">{{ column.label }}</th>
            <th v-if="actions"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in paginatedData" :key="item.id">
            <td v-for="column in columns" :key="column.key">{{ item[column.key] }}</td>
            <td v-if="actions">
              <button v-for="action in actions" :key="action.name" @click="handleAction(action, item)" :class="action.class">
                {{ action.label }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="pagination" class="pb-3 d-flex justify-content-center">
        <div>
            <button @click="prevPage" :disabled="currentPage <= 1">Previous</button>
            <span>Page {{ currentPage }} of {{ totalPages }}</span>
            <button @click="nextPage" :disabled="currentPage >= totalPages">Next</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'DataTable',
    props: {
      data: {
        type: Array,
        required: true
      },
      columns: {
        type: Array,
        required: true
      },
      actions: {
        type: Array,
        default: () => []
      },
      pagination: {
        type: Boolean,
        default: false
      },
      itemsPerPage: {
        type: Number,
        default: 10
      }
    },
    data() {
      return {
        currentPage: 1
      };
    },
    computed: {
      paginatedData() {
        if (!this.pagination) return this.data;
  
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.data.slice(start, end);
      },
      totalPages() {
        return Math.ceil(this.data.length / this.itemsPerPage);
      }
    },
    methods: {
      handleAction(action, item) {
        this.$emit('action', { action, item });
      },
      prevPage() {
        if (this.currentPage > 1) {
          this.currentPage--;
        }
      },
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.currentPage++;
        }
      }
    }
  }
  </script>
  
  <style scoped>
  /* Add styles for pagination buttons */
  button {
    margin: 0 5px;
  }
  </style>
  
  
  <style scoped>
  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
    border-radius: 100px!important;

  }
  
  th, td {
    border-bottom: 1px solid #ddd;
    padding: 8px;
  }
  th {
    padding-top: 10px;
    background-color: #649ee0 ;
    font-weight: 700;
    font-size: 12px;
    color: white;
    text-transform: uppercase;
  }
  td{
    font-weight: 500;
    font-size: 13px;
    padding: 10px 10px 10px 10px;
  }
  .edit-btn {
    background-color: transparent;
    border: 1px solid rgba(0, 0, 0, 0);
    border-radius: 5px;
    box-shadow: rgba(14, 63, 126, 0.06) 0px 0px 0px 1px, rgba(42, 51, 70, 0.03) 0px 1px 1px -0.5px, rgba(42, 51, 70, 0.04) 0px 2px 2px -1px, rgba(42, 51, 70, 0.04) 0px 3px 3px -1.5px, rgba(42, 51, 70, 0.03) 0px 5px 5px -2.5px, rgba(42, 51, 70, 0.03) 0px 10px 10px -5px, rgba(42, 51, 70, 0.03) 0px 24px 24px -8px;
    padding: 5px;
    background-color: #007bff;
    color: white;
    width: 70px;
  }
  .delete-btn {
    background-color: transparent;
    border: 1px solid rgba(0, 0, 0, 0);
    border-radius: 5px;
    box-shadow: rgba(14, 63, 126, 0.06) 0px 0px 0px 1px, rgba(42, 51, 70, 0.03) 0px 1px 1px -0.5px, rgba(42, 51, 70, 0.04) 0px 2px 2px -1px, rgba(42, 51, 70, 0.04) 0px 3px 3px -1.5px, rgba(42, 51, 70, 0.03) 0px 5px 5px -2.5px, rgba(42, 51, 70, 0.03) 0px 10px 10px -5px, rgba(42, 51, 70, 0.03) 0px 24px 24px -8px;
    padding: 5px;
    background-color: #dc3545;
    color: white;
    width: 70px;
  }
  </style>
  