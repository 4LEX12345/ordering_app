<template>
    <div class="p-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Order</li>
                <li class="breadcrumb-item active" aria-current="page"><a href="order">Order List</a></li>
            </ol>
        </nav>

      <div class="main-container p-5 pt-3 " >
        <div class="row mb-2" v-show="!toggleCreate && !toggleEdit">
          <div class="col-md-12 text-right">
            <div class="">
              <button class="custom-add-btn rounded pl-3 pr-3 pt-2 pb-2" style="width: 10rem;" v-on:click="create">Create Order</button>
            </div>
          </div>
        </div>

        <div class="table" v-show="!toggleCreate && !toggleEdit ">
          <ordertable-component :tableData="tableData" @edit="edit" @destroy="destroy"></ordertable-component>
        </div> 

        <div class="form" >
            <order-form-component 
                v-show="toggleCreate"
                v-if="isRendered" 
                :toggleCreate="toggleCreate"
                @cancelCreation="cancelCreation"
                :customer-information="customerInformation"
                :dateToday="dateToday"
                :trackingNumber="trackingNumber"
                :productsData="productsData"
            ></order-form-component>
        </div> 

        <LoadingOverlayComponent :isVisible="loading" />
      </div>  
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { toast } from 'vue3-toastify';
  import Swal from 'sweetalert2';
  import LoadingOverlayComponent from '../utilities/LoadingOverlayComponent.vue'
  import OrdertableComponent from './OrdertableComponent.vue';
  import OrderFormComponent from './OrderFormComponent.vue';
  
  export default {
    components:{LoadingOverlayComponent, OrdertableComponent, OrderFormComponent},
    data() {
      return {

        //default 
        loading: false,
        isEdit : false,
        toggleCreate : false,
        toggleEdit : false,
        isRendered : false,
        errors : [],

        //collections
        tableData:[], 
        productsData : [],
   
        //variables
        customerInformation : {
            business_name : '',
            business_tax_id : '',
            contact_person : '',
            contact_person_contact_number : '',
            contact_person_email : '',
            billing_address : '',
            shipping_address : '',
            wholesale_account_number : '',
        },
        dateToday : '',
        trackingNumber : '',

      };
    },
    methods: {
        init(){
            this.customerInformation = {
                business_name : '',
                contact_person : '',
                business_tax_id : '',
                contact_person_contact_number : '',
                contact_person_email : '',
                billing_address : '',
                shipping_address : '',
            };
        },
        async getData(){
         await axios.get('order/fetchdata').then(response => 
          {
            this.dateToday  = response.data.date_today;
            this.trackingNumber = response.data.tracking_number;
            this.productsData = response.data.products;
            this.isRendered = true; //to make sure the fetchdata rendered first. this will solve the reactivity of the child-parent component
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
        this.isEdit = false;
        this.init();
        this.toggleCreate = true;
      },
      edit(row){
    
      },
      cancelCreation(val){
        this.toggleCreate  = false;
        this.toggleEdit  = false;
      },
      async store(data, image){
       

      },
      async update(data, image){
      
      },
      async destroy(id){
      
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
  