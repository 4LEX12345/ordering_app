<template>
    <div class="p-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Product</li>
                <li class="breadcrumb-item active" aria-current="page"><a href="product_category">Product List</a></li>
            </ol>
        </nav>

      <div class="main-container p-5 pt-3 " >
        <div class="row mb-2" v-show="!toggleCreate && !toggleEdit">
          <div class="col-md-12">
            <div class="">
              <button class="custom-add-btn rounded pl-3 pr-3 pt-2 pb-2" v-on:click="create"><i class="fas fa-solid fa-plus"></i></button>
            </div>
          </div>
        </div>

        <div class="table" v-show="!toggleCreate && !toggleEdit ">
          <product-table-component :tableData="tableData" @edit="edit" @destroy="destroy"></product-table-component>
        </div> 

        <div class="form" >
          <!-- create -->
          <product-form-component 
            v-show="toggleCreate"
            :toggleCreate="toggleCreate"
            :productData="product"
            @cancelCreation="cancelCreation"
            :brands = "brands"
            :flavors = "flavors"
            :productCategories = "product_categories"
            :errors="errors"
            @store="store"
            >
          </product-form-component>

          <!-- edit -->
          <product-form-component 
            v-show="toggleEdit"
            :toggleEdit="toggleEdit" 
            :productData="product"
            @cancelCreation="cancelCreation" 
            :brands = "brands"
            :flavors = "flavors"
            :productCategories = "product_categories"
            :errors="errors"
             @update="update"
           >
          </product-form-component>
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
  import ProductTableComponent from './ProductTableComponent.vue';
  import ProductFormComponent from './ProductFormComponent.vue';


  export default {
    components:{LoadingOverlayComponent, ProductTableComponent, ProductFormComponent},
    data() {
      return {

        //default 
        loading: false,
        isEdit : false,
        toggleCreate : false,
        toggleEdit : false,
        errors : [],

        //table for product category
        tableData:[],
   
        //variables
        product  : {},
        brands : [],
        flavors : [],
        product_categories : [],

      };
    },
    methods: {
      async getData(){
         await axios.get('product/fetchdata').then(response => 
          {
            this.tableData = response.data.products.map(product => ({
              ...product,
              brand : product.brand.name,
              flavor : product.flavor.name,
              product_category : product.product_category.name,
            }));
            this.brands = response.data.brands;
            this.flavors = response.data.flavors;
            this.product_categories = response.data.product_categories;
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
        this.product = {};
        this.toggleCreate = true;
      },
      edit(row){
        this.toggleEdit = true;
        this.toggleEdit = true;
        this.product = row;
      },
      cancelCreation(val){
        this.getData();
        this.errors = [];
        this.product = {};
        this.toggleCreate = val;
        this.toggleEdit = val;
      },
      async store(data, image){
          const formData = new FormData();
          formData.append('image', image);
          formData.append('name', data.name === undefined ? '' :  data.name);
          formData.append('description', data.description === undefined ? '' :  data.description);
          formData.append('brand_id', data.brand_id === undefined ? '' :  data.brand_id);
          formData.append('product_category_id', data.product_category_id === undefined ? '' :  data.product_category_id);
          formData.append('flavor_id', data.flavor_id  === undefined ? '' :  data.flavor_id);
          formData.append('unit_price', data.unit_price  === undefined ? '' :  data.unit_price);

          this.loading = true;
          await axios.post('product/store', formData,{
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
              this.getData();
              this.cancelCreation(false);
          }).catch(error => {
            this.errors = error.response.data.errors;
            console.log(this.errors);
          }).finally(() => {this.loading = false});

      },
      async update(data, image){
        const formData = new FormData();
        formData.append('image', image);
        formData.append('id', data.id);
        formData.append('name', data.name === undefined ? '' :  data.name);
        formData.append('description', data.description === undefined ? '' :  data.description);
        formData.append('brand_id', data.brand_id === undefined ? '' :  data.brand_id);
        formData.append('product_category_id', data.product_category_id === undefined ? '' :  data.product_category_id);
        formData.append('flavor_id', data.flavor_id  === undefined ? '' :  data.flavor_id);
        formData.append('unit_price', data.unit_price  === undefined ? '' :  data.unit_price);
        formData.append('_method', 'POST');
       
        this.loading = true;
        await axios.post('product/update', formData).then(response => {
          toast.success(response.data.message, {
            position: toast.POSITION.TOP_RIGHT,
            autoClose: 3000,
            hideProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
          });
          this.getData();
          this.cancelCreation(false);
        }) .catch(error => {
          this.errors = error.response.data.errors;
        }).finally(() => { this.loading = false });
      },
      async destroy(id){
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
            axios.get('product/destroy/' + id).then(response => {
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
  