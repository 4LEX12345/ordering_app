<template>
    <div class="p-3">
        <nav aria-label="breadcrumb" >
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Order</li>
                <li class="breadcrumb-item active" aria-current="page"><a href="order">Order List</a></li>
            </ol>
        </nav>

      <div class="main-container p-5 pt-3 "  >
        <div class="row mb-2" v-show="!toggleCreate && !toggleEdit">
          <div class="col-md-12 text-right">
            <div class="m-auto text-right">
              <button class="button create-btn create-btn:hover" dropdown-toggle="dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Create New &nbsp; ▼</button>
              <div class="dropdown-menu">
                <a class="dropdown-item" v-on:click="create">New</a>
                <a class="dropdown-item" v-on:click="create">Existing</a>
              </div>
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
                :errors="errors"
                @store="store"
            ></order-form-component>


            <order-view-component 
                v-if="toggleEdit"
                :toggleCreate="toggleEdit"
                @cancelCreation="cancelCreation"
                :orderData="orderData"
                :orderDetails="orderDetails"
                :errors="errors"
                @update="update"
                @store="store"
                @generateInvoice="generateInvoice"
                @generateFinalInvoice="generateFinalInvoice"
            ></order-view-component>

        </div> 

        <LoadingOverlayComponent :isVisible="loading" />
       
      </div>  
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { toast } from 'vue3-toastify';
  import Swal from 'sweetalert2';
  import LoadingOverlayComponent from '../utilities/LoadingOverlayComponent.vue';
  import OrdertableComponent from './OrderTableComponent.vue';
  import OrderFormComponent from './OrderFormComponent.vue';
  import OrderViewComponent from './OrderViewComponent.vue';
  
  export default {
    components:{LoadingOverlayComponent, OrdertableComponent, OrderFormComponent,OrderViewComponent},
    data() {
      return {

        //default 
        loading: false,
        // generateLoading: false,
        isEdit : false,
        toggleCreate : false,
        toggleEdit : false,
        isRendered : false,
        errors : [],

        //collections
        tableData:[], 
        productsData : [],
        orderData : {},
        orderDetails : [],
   
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
      create(){
        this.isEdit = false;
        this.init();
        this.toggleCreate = true;
      },
      cancelCreation(val){
        this.toggleCreate  = false;
        this.toggleEdit  = false;
      },
      async getData(){
         await axios.get('order/fetchdata').then(response => 
          {
            this.dateToday  = response.data.date_today;
            this.trackingNumber = response.data.tracking_number;
            this.productsData = response.data.products;
            this.tableData = response.data.orders;
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
      async edit(row){
        this.loading = true;
        await axios.get('/order/edit/' + row.id).then(response => {
          this.orderData = response.data.data;
          this.orderDetails = response.data.order_details;
          this.toggleEdit = true;
          this.toggleCreate = false;
        }).catch(errors => {
          toast.warning('Sorry Something Went Wrong!', {
              position: toast.POSITION.TOP_RIGHT,
              autoClose: 3000,
              hideProgressBar: true,
              closeOnClick: true,
              pauseOnHover: true,
              draggable: true,
            });
        }).finally(() => { this.loading = false});
   
       
      },
      async store(data, image, items, orderInfo, subTotal){
        const formData = new FormData();
        formData.append('image', image);
        formData.append('business_name', data.business_name);
        formData.append('contact_person', data.contact_person);
        formData.append('contact_person_contact_number', data.contact_person_contact_number);
        formData.append('contact_person_email', data.contact_person_email);
        formData.append('billing_address', data.billing_address);
        formData.append('shipping_address', data.shipping_address);
        formData.append('order_date', orderInfo.order_date);
        formData.append('tracking_number', orderInfo.tracking_number);
        formData.append('payment_method', orderInfo.payment_method);
        formData.append('payment', orderInfo.payment);
        formData.append('sub_total', subTotal);
        items.forEach((item, index) => {
          formData.append(`items[${index}]`,  JSON.stringify(item));
        });
        this.loading = true;
        await axios.post('order/store', formData,{
        headers: {
          'Content-Type' : 'multipart/form-data',
        }
        }).then(response => {
            toast.success(response.data.message, {
              position: toast.POSITION.TOP_RIGHT,
              autoClose: 3000,
              hideProgressBar: true,
              closeOnClick: true,
              pauseOnHover: true,
              draggable: true,
            });
            
            if(response.data.is_paid){
              this.generateFinalInvoice(response.data.order_id);
            }
            else{
              this.generateInvoice(response.data.order_id);

            }
    
        }).catch(error => {
          this.errors = error.response.data.errors;
          console.log(this.errors);
        }).finally(() => {this.loading = false});
      },
      async generateInvoice(id){
        await axios.get('order/generateinvoice/' + id).then(response => {
            if(response.data.message == 'success'){
              window.open('order/showinvoice', '_blank');
            }
            this.getData();
            this.cancelCreation(false);
        }).catch(errors => {
          toast.warning('Sorry Something Went Wrong On Generating Invoice!', {
              position: toast.POSITION.TOP_RIGHT,
              autoClose: 3000,
              hideProgressBar: true,
              closeOnClick: true,
              pauseOnHover: true,
              draggable: true,
            });
        });
      },
      async generateFinalInvoice(id){
        await axios.get('order/generatefinalinvoice/' + id).then(response => {
            if(response.data.message == 'success'){
              window.open('order/showfinalinvoice', '_blank');
            }
            this.getData();
            this.cancelCreation(false);
        }).catch(errors => {
          toast.warning('Sorry Something Went Wrong On Generating Invoice!', {
              position: toast.POSITION.TOP_RIGHT,
              autoClose: 3000,
              hideProgressBar: true,
              closeOnClick: true,
              pauseOnHover: true,
              draggable: true,
            });
        });
      },
      async update(data, id){
        this.loading = true;
        await axios.put('order/update/' + id, {
          data : data
        }).then(response => {
          this.orderData = response.data.data;
          this.orderDetails = response.data.order_details;
        }).catch(errors => {
          toast.warning('Sorry Something Went Wrong!', {
              position: toast.POSITION.TOP_RIGHT,
              autoClose: 3000,
              hideProgressBar: true,
              closeOnClick: true,
              pauseOnHover: true,
              draggable: true,
            });
        }).finally(() => { this.loading = false});
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
    .dropdown-menu{
      cursor: pointer;
    }
    
  </style>
  