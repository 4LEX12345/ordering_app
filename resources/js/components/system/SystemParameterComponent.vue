<template>
    <div class="p-3">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">System</li>
                <li class="breadcrumb-item active" aria-current="page"><a href="system_parameter">System Parameter</a></li>
          </ol>
        </nav>
        <div class="main-container p-5 pt-3 ">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="invoice_num">Invoice Number</label>
                        <input v-model="system_parameter.invoice_num" type="text"  id="invoice_num" :class="{'form-control': true, 'border border-danger': errors.invoice_num}">
                        <span v-if="errors.invoice_num" class="error-message">{{ errors.invoice_num[0]}}</span>
                    </div>
                    <div class="form-group">
                        <label for="tracking_number">Tracking Number</label>
                        <input v-model="system_parameter.tracking_number" type="text"  id="tracking_number" :class="{'form-control': true, 'border border-danger': errors.tracking_number}">
                        <span v-if="errors.tracking_number" class="error-message">{{ errors.tracking_number[0]}}</span>
                    </div>
                    <div class="form-group">
                      <button class="button create-btn w-100 m-0 mt-3" v-on:click="store">Save</button>
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

        //variables
        system_parameter  : [],

      };
    },
    methods: {
      getData(){
        axios.get('systemparameter/fetchdata').then(response => 
          {
            this.system_parameter = response.data.system_parameter;
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
      store(){
        this.loading = true;
        axios.post('systemparameter/store', {
            invoice_num : this.system_parameter.invoice_num,
            tracking_number : this.system_parameter.tracking_number,
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
    },
    created() {
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
  