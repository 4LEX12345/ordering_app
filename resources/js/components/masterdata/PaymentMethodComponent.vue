<template>
  <div class="p-3">
      <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Master Data</li>
              <li class="breadcrumb-item active" aria-current="page"><a href="brand">Payment Method</a></li>
        </ol>
      </nav>
      <div class="row mb-4 mt-4">
        <div class="col-md-12">
          <div class="m-auto text-right">
            <button class="button create-btn create-btn:hover" v-on:click="create">Create New <i class="fas fa-solid fa-plus"></i></button>
          </div>
        </div>
      </div>

      <div class="main-container p-5 pt-3 ">
        <div id="people">
            <v-client-table  :data="tableData" :columns="columns" :options="options">
              <template v-slot:role="{ row }">
                <span>{{ formatRoles(row) }}</span>
              </template>
              <template v-slot:actions="{ row }">
                <button class="button edit-btn"  v-on:click="edit(row)"> <i class="fas fa-edit text-primary text-light"></i> Edit</button>
                <button class="button delete-btn" v-on:click="destroy(row)"> <i class="fas fa-trash text-light"></i> Delete</button>
              </template>
            </v-client-table>
        </div> 

        <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" >Add Payment Method</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="first_name">Payment Method</label>
                            <input v-model="paymentMethod.name" type="text"  id="first_name" :class="{'form-control': true, 'border border-danger': errors.name}">
                            <span v-if="errors.name" class="error-message">{{ errors.name[0]}}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="button close-btn" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="button save-btn" v-on:click="store" v-if="!isEdit">Create</button>
                        <button type="button" class="button update-btn"  v-on:click="update"  v-else>Update</button>
                    </div>
                </div>
            </div>
        </div>
        <LoadingOverlayComponent :isVisible="loading" />
    </div>  
  </div>
</template>

<script>
import axios from 'axios';
import LoadingOverlayComponent from '../utilities/LoadingOverlayComponent.vue';
import { toast } from 'vue3-toastify';
import Swal from 'sweetalert2';

export default {
  components:{LoadingOverlayComponent},
  data() {
    return {

      //default 
      loading: false,
      isEdit : false,
      errors : [],

      //table for brand
      tableData:[],
      columns: ['id', 'name','actions'],
      options: {
          pagination : {
            virtual : true,
          },
          filterable:  false, //filter change when needed
      },

      //variables
      paymentMethod  : [],

    };
  },
  methods: {
    init(){
      this.paymentMethod.name = '';
    },
    getData(){
      axios.get('paymentmethod/fetchdata').then(response => 
        {
          this.tableData = response.data.payment_methods;
        }).catch(error => {
          toast.warning('Sorry Something Went Wrong!', {
            position: toast.POSITION.TOP_RIGHT,
            autoClose: 3000,
            hideProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
          });
        });
    },
    create(){
      this.paymentMethod.name = '';
      this.isEdit = false;
      $('#create-modal').modal('show');
    },
    store(){
      this.loading = true;
      axios.post('paymentmethod/store', {
          name : this.paymentMethod.name,
      }).then(response => {
        toast.success(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
          hideProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true,
          draggable: true,
        });
        this.getData();
        $('#create-modal').modal('hide');
      }) .catch(error => {
          this.errors = error.response.data.errors;
      }).finally(() => { this.loading = false });
    },
    edit(row){
      this.isEdit = true;
      this.paymentMethod.id = row.id;
      this.paymentMethod.name = row.name;
      $('#create-modal').modal('show');
    },
    update(){
      this.loading = true;
      axios.put(`paymentmethod/update/${this.paymentMethod.id}`, {
          name : this.paymentMethod.name,
      }).then(response => {
        toast.success(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
          hideProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true,
          draggable: true,
        });
        this.getData();
        $('#create-modal').modal('hide');
      }) .catch(error => {
          this.errors = error.response.data.errors;
      }).finally(() => { this.loading = false });
    },
    async destroy(row){
        const result = await Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
        });

        if(result.isConfirmed){
          this.loading = true;
          axios.get('paymentmethod/destroy/' + row.id).then(response => {
            toast.success(response.data.message, {
              position: toast.POSITION.TOP_RIGHT,
              autoClose: 3000,
              hideProgressBar: true,
              closeOnClick: true,
              pauseOnHover: true,
              draggable: true,
            });
            this.getData();
          }).catch(error => {
            toast.warning('Sorry Something Went Wrong!', {
              position: toast.POSITION.TOP_RIGHT,
              autoClose: 3000,
              hideProgressBar: true,
              closeOnClick: true,
              pauseOnHover: true,
              draggable: true,
            });
          }).finally(() => {this.loading = false})
        }
    },
  },
  created() {
      this.init();
      this.getData();
  }
}
</script>

<style scoped>
  .main-container{
    box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    background-color: white;
    border-radius: 10px;
  }
  .header-card{
    background-color: white;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    padding: 10px;
  }
  .header-card p{
    margin: 0;
    padding: 0;
  }
  .tab-title{
    font-weight: 500;
    margin: 0;
    padding : 10px;
  }
</style>
