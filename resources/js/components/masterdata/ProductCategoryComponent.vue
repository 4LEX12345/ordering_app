<template>
    <div class="p-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active" aria-current="page"><a href="product_category">Product Category</a></li>
            </ol>
        </nav>

      <div class="main-container p-5 pt-3 ">
        <div class="row mb-2">
        <div class="col-md-12">
          <div class="">
            <button class="custom-add-btn rounded pl-3 pr-3 pt-2 pb-2" v-on:click="create"><i class="fas fa-solid fa-plus"></i></button>
          </div>
        </div>
        </div>

        <div id="people">
            <v-client-table  :data="tableData" :columns="columns" :options="options">
              <template v-slot:role="{ row }">
                <span>{{ formatRoles(row) }}</span>
              </template>
              <template v-slot:actions="{ row }">
                <button class="btn" v-on:click="edit(row)"><i class="fas fa-edit text-primary"></i></button>
                <button class="btn" v-on:click="destroy(row)"><i class="fas fa-trash text-danger"></i></button>
              </template>
            </v-client-table>
        </div> 

        <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" >Add Brand</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="first_name">Product Category Name</label>
                            <input v-model="productCategory.name" type="text"  id="first_name" :class="{'form-control': true, 'border border-danger': errors.name}">
                            <span v-if="errors.name" class="error-message">{{ errors.name[0]}}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" v-on:click="store" v-if="!isEdit">Create</button>
                        <button type="button" class="btn btn-primary"  v-on:click="update"  v-else>Update</button>
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

        //table for product category
        tableData:[],
        columns: ['id', 'name','actions'],
        options: {
            pagination : {
              virtual : true,
            },
            filterable:  false, //filter change when needed
        },

        //variables
        productCategory  : [],

      };
    },
    methods: {
      init(){
        this.productCategory.name = '';
      },
      getData(){
        axios.get('productcategory/fetchdata').then(response => 
          {
            this.tableData = response.data.product_categories;
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
        this.productCategory.name = '';
        this.isEdit = false;
        $('#create-modal').modal('show');
      },
      store(){
        this.loading = true;
        axios.post('productcategory/store', {
            name : this.productCategory.name,
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
        this.productCategory.id = row.id;
        this.productCategory.name = row.name;
        $('#create-modal').modal('show');
      },
      update(){
        this.loading = true;
        axios.put(`productcategory/update/${this.productCategory.id}`, {
            name : this.productCategory.name,
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
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
          });

          if(result.isConfirmed){
            this.loading = true;
            axios.get('productcategory/destroy/' + row.id).then(response => {
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
    .custom-add-btn{
      background-color: transparent;
      border: 1px solid rgba(0, 0, 0, 0);
      border-radius: 1px;
      box-shadow: rgba(14, 63, 126, 0.06) 0px 0px 0px 1px, rgba(42, 51, 70, 0.03) 0px 1px 1px -0.5px, rgba(42, 51, 70, 0.04) 0px 2px 2px -1px, rgba(42, 51, 70, 0.04) 0px 3px 3px -1.5px, rgba(42, 51, 70, 0.03) 0px 5px 5px -2.5px, rgba(42, 51, 70, 0.03) 0px 10px 10px -5px, rgba(42, 51, 70, 0.03) 0px 24px 24px -8px;
      padding: 2px;
      background-color: #28a745;
      color: white;
      width: 50px;
      margin-right: 20px;
    }
  </style>
  