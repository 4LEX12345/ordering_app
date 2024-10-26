<template>
    <div>

        <div class="header mb-4">
            <h1> {{ customerInformationForm ? 'Customer Information' : 'Your Order' }}</h1>
            <div class="w-100 border-bottom mt-3 mb-2"></div>
        </div>
        <div class="row customer-information" v-if="customerInformationForm">
            <div class="col-md-6"> 
                <div class="form-group">
                    <label for="business_name">Business Name</label>
                    <input v-model="customerInformation.business_name" type="text"  id="business_name" :class="{'form-control': true, 'border border-danger': errors.business_name}">
                    <span v-if="errors.business_name" class="error-message">{{ errors.business_name[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="billing_address">Billing Address <span style="font-style: italic; color:grey; font-size: 10px;">Please separate the desrciption by comma (,)</span></label>
                    <textarea name="billing_address" id="billing_address" rows="4" cols="4" v-model="customerInformation.billing_address" :class="{'form-control': true, 'border border-danger': errors.brand_id}"></textarea>
                    <span v-if="errors.billing_address" class="error-message">{{ errors.billing_address[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="shipping_address">Shipping Address <span style="font-style: italic; color:grey; font-size: 10px;">Please separate the desrciption by comma (,)</span></label>
                    <textarea name="shipping_address" id="shipping_address" rows="4" cols="4" v-model="customerInformation.shipping_address" :class="{'form-control': true, 'border border-danger': errors.shipping_address}"></textarea>
                    <span v-if="errors.shipping_address" class="error-message">{{ errors.shipping_address[0]}}</span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="flavor_id">Contact Person</label>
                    <input v-model="customerInformation.contact_person" type="text"  id="contact_person" placeholder="Juan Dela Cruz" :class="{'form-control': true, 'border border-danger': errors.contact_person}">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_person_contact_number">Contact Person Contact Number</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">+63</span>
                                <input type="text" class="form-control" placeholder="9XXXXXXXXX" aria-label="Username" aria-describedby="basic-addon1" v-model="customerInformation.contact_person_contact_number">
                            </div>
                            <span v-if="errors.contact_person_contact_number" class="error-message">{{ errors.contact_person_contact_number}}</span>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_person_email">Contact Person Email</label>
                            <div class="input-group mb-3">
                                <input v-model="customerInformation.contact_person_email" type="text"  id="contact_person_email" class="form-control" placeholder="example@gmail.com">
                            </div>
                            <span v-if="errors.contact_person_email" class="error-message">{{ errors.contact_person_email}}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Upload <b>BIR 2303</b> </label>
                            <input class="form-control" type="file" id="formFile"@change="onFileChange"  />
                        </div>
                    </div>
                </div>
            </div>

         
        </div>

        <div class="items mb-5" v-if="!customerInformationForm">
            <div class="row">
                <div class="col-md-7">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success mr-2" v-on:click="addProduct()">Add Item</button>
                    </div>
                    <v-client-table  :data="items" :columns="columns" :options="options">
                    <template v-slot:role="{ row }">
                        <span>{{ formatRoles(row) }}</span>
                    </template>
                    <template v-slot:actions="{ row }" class="">
                        <button class="btn m-auto" v-on:click="removeItem(row)"><i class="fas fa-trash text-danger"></i></button>
                    </template>
                </v-client-table>
                </div>

                <div class="payment-info col-md-5">
                    <div class="payment-head">
                        <p class="title-header">Payment Information</p>
                        <div class="row payment_information">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Tracking Number</label>
                                    <input type="text" class="form-control" readonly v-model="orderInfo.tracking_number">
                                    <span v-if="errors.tracking_number" class="error-message" style="color: red;">{{ errors.tracking_number[0]}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_date">Order Date</label>
                                    <input type="date" class="form-control" name="order_date"  id="order_date" v-model="orderInfo.order_date">
                                    <span v-if="errors.order_date" class="error-message" style="color: red;">{{ errors.order_date[0]}}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="payment_method">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-control" v-model="orderInfo.payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="1">Credit Card</option>
                                        <option value="2">Debit Card</option>
                                        <option value="3">Bank Transfer</option>
                                        <option value="4">Cash on Delivery</option>
                                    </select>
                                    <span v-if="errors.payment_method" class="error-message" style="color: red;">{{ errors.payment_method[0]}}</span>
                                </div>
                            </div> 
                            <div class="col-md-12" >
                                <div class="d-md-flex">
                                    <button class="btn btn-secondary" style="font-size: 10px;" v-on:click="togglePayment">Add Payment</button>
                                    <div class="ml-2" v-if="inputPayment">
                                        <div class="d-md-flex" v-show="inputPayment">
                                        <input type="text" class="form-control" v-model="payment" style="font-size: 10px;">
                                        <button class="btn btn-primary ml-2" style="font-size: 8px;" v-on:click="addPayment">+  </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="order-overview mt-2">
                        <p class="title-header">Order Summary</p>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <p>Subtotal</p>
                            </div>
                            <div class="col-6">
                                <p class="text-right">{{ formatNumber(subTotal) }}</p>
                            </div>
                        </div>
                        <div class="row justify-content-between" v-show="hasPayment">
                            <div class="col-6">
                                <p>Payment</p>
                            </div>
                            <div class="col-6">
                                <p class="text-right">{{ formatNumber(orderInfo.payment) }}</p>
                            </div>
                        </div>
                        <div class="w-100 border-bottom mt-2 mb-2"></div>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <p class="font-weight-bold">TOTAL</p>
                            </div>
                            <div class="col-6">
                                <p class="text-right font-weight-bold">{{ grandTotal }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 text-right">
            <button class="btn btn-warning mr-2" v-on:click="toogleCancel()">Cancel</button>
            <button class="btn btn-primary mr-2" v-show="toggleCreate" v-if="customerInformationForm" v-on:click="createOrder" :disabled="!isInputComplete">Proceed to create order</button>
            <!-- <button class="btn btn-primary mr-2 " v-show="toggleCreate" v-if="!customerInformationForm" v-on:click="store" >Save</button> -->
            <button class="btn btn-primary" v-show="toggleCreate" v-if="!customerInformationForm" v-on:click="store" >Save and Generate Invoice</button>
        </div>

        <div class="modal fade" id="product-modal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" >Product List</h5>
                        <button class="btn btn-danger" v-on:click="closeModal"><i class="fas fa-times-circle"></i></button>
                    </div>
                    <div class="modal-body" >
                        <div class="form-group m-auto pb-3 " style="width: 99%;">
                            <label for="search">Search Product</label>
                            <input type="text" placeholder="Seach product name here...." class="form-control" v-model="searchItem" v-on:change="searchProduct">
                        </div>
                        <div style="height: 500px; overflow-y: scroll; overflow-x: hidden; width: 100%;" class="p-2 ">
                            <div class="row">
                                <div class="col-md-4" v-for="item in productFilter ">
                                    <div class="card m-3">
                                        <img :src="'/storage/'+ item.image" alt="test" width="150" height="150" class="m-auto p-3">
                                        <div class="card-body">
                                            <span style="font-size: 15px; font-weight: 400;">Name : {{ item.name}}  </span>
                                            <br>
                                            <span>Brand : <span>{{ item.brand_name}} </span> </span>
                                            <br>
                                            <span>Flavor : <span>{{ item.flavor_name}} </span> </span>
                                            <br>
                                            <span>Category : <span>{{ item.product_category_name}} </span> </span>
                                            <br>
                                            <div class="form-group mt-3">
                                                <label for="">Price</label>
                                                <input type="text" v-model="item.unit_price" class="form-control" readonly>
                                            </div>
                                            <div class="mt-3">
                                                <div class="form-group">
                                                <label for="">Quantity</label>
                                                <input type="text" v-model="item.input_quantity" class="form-control">
                                            </div>
                                         
                                            </div>
                                        </div>
                                        <div class="mb-3 p-2">
                                            <button class="w-100 btn btn-primary" v-on:click="addItem(item)">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         
                        </div>
                    </div>
                    <div class="modal-footer">
                     
                    </div>
                </div>
            </div>
        </div>

        <LoadingOverlayComponent :isVisible="loading" style="z-index: 99999!important;" />
    </div>
</template>

<script>
    import axios from 'axios';
    import { toast } from 'vue3-toastify';
    import Swal from 'sweetalert2';
    import LoadingOverlayComponent from '../utilities/LoadingOverlayComponent.vue';

    export default {
        components:{LoadingOverlayComponent},
        props : {
            errors : {
                type : Object,
                default : {},
            },  
            customerInformation :{
                type : Object,
                default : {},
            },  
            dateToday :{
                type : String,
            }, 
            trackingNumber : {
                type : String,
            } ,
            toggleCreate : {
                type : Boolean,
                default : false,
            },
            toggleEdit : {
                type : Boolean,
                default : false,
            },
            productsData :{
                type : Array,
                default : [],
            }
        },  
        data(){
            return {
                //default 
                loading: false,

                //variables
                file: null,
                business_tax_file : [],
                orderInfo : {
                    order_date : '',
                    tracking_number : '',
                    payment_method : '',
                    payment : 0,
                },
                customerInformationForm : true,
                searchItem : '',
                productList : [],
                payment : 0,
                inputPayment : false,

                //table
                items : [],
                columns: [ 'name','input_quantity','unit_price','total','actions'],
                options: {
                    headings : {
                        name : 'Product',
                        input_quantity : 'Input Quantity',
                    },
                    pagination : {
                    virtual : true,
                    },
                    templates: {
                        created_at: function(h, row) {
                        return row.created_at !== null ? moment(row.created_at).format('YYYY-MM-DD hh:mm:ss') : null;
                        },
                        updated_at: function(h, row) {
                            return row.updated_at !== null ? moment(row.updated_at).format('YYYY-MM-DD hh:mm:ss') : null;
                        },
                        total: function(h, row){
                            const totalValue = Number(row.input_quantity) * Number(row.unit_price);
                            return ('span', {}, `${totalValue.toFixed(2)}`); // Return formatted total
                        }
                    },
                    filterable:  false, //filter change when needed
                },
           
            }
        },
        mounted(){
            if(this.productsData){
                this.productList = this.productsData;
            }
            if (this.dateToday) {
                this.orderInfo.order_date = this.dateToday;
            }
            if (this.trackingNumber) {
                this.orderInfo.tracking_number = this.trackingNumber;
            }
        },
        methods : {
            async toogleCancel(){
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
                    setTimeout(() => {
                        this.loading = false;
                        this.customerInformationForm = true;
                        this.$emit('cancelCreation', false);
                    }, 1000);
                }
            },
            createOrder(){
                this.loading = true;
                setTimeout(() => {
                    this.customerInformationForm = false;
                    this.loading = false;
                }, 1000);
            },
            store(){
                this.$emit('store',this.customerInformation, this.business_tax_file, this.items, this.orderInfo, this.subTotal);
            },
            update(){
                this.$emit('update',this.customerInformation, this.image);
            },
            onFileChange(event) {
                this.business_tax_file = event.target.files[0]; // Get the selected file
                this.customerInformation.business_tax_id =   this.business_tax_file.name;
            },
            addItem(item){
                if(item.input_quantity != 0){
                    this.loading  = true;
                    setTimeout(() => {
                        const getItem = {...item};
                        this.items.push(getItem);
                        this.loading = false;

                        let index = this.productList.findIndex(product => product.id == item.id);
                        console.log(item, index);
                        if(index != -1){
                            this.productList.splice(index, 1);
                        }
                        toast.success('Item Added!', {
                            position: toast.POSITION.TOP_RIGHT,
                            autoClose: 3000,
                            hideProgressBar: true,
                            closeOnClick: true,
                            pauseOnHover: true,
                            draggable: true,
                        });
                    }, 500);
                } 
                else{
                    toast.warning('PLEASE INPUT QUANTITY / UNIT PRICE', {
                        position: toast.POSITION.TOP_RIGHT,
                        autoClose: 3000,
                        hideProgressBar: true,
                        closeOnClick: true,
                        pauseOnHover: true,
                        draggable: true,
                    });
                }
            },
            removeItem(row){
                row.input_quantity = 0;
                this.loading  = true;
                setTimeout(() => {
                    const getItem = {...row};
                    this.productList.unshift(getItem);

                    let index = this.items.findIndex(product => product.id == row.id);
                    if(index != -1){
                        this.items.splice(index, 1);
                    }
                    toast.success('Item Removed!', {
                        position: toast.POSITION.TOP_RIGHT,
                        autoClose: 3000,
                        hideProgressBar: true,
                        closeOnClick: true,
                        pauseOnHover: true,
                        draggable: true,
                    });
                    this.loading = false;
                }, 500);
            },
            addProduct(){
                this.productList = this.productsData;
                $('#product-modal').modal('show');
            },
            closeModal(){
                $('#product-modal').modal('hide');
            },
            addPayment(){
                this.orderInfo.payment = this.payment;
            },
            formatNumber(value) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency', // or 'decimal'
                    currency: 'PHP', // Change this if needed, e.g., 'EUR', 'GBP'
                    minimumFractionDigits: 2, // Ensures two decimal places
                }).format(value);
            },
            togglePayment(){
                this.inputPayment = true;
            }
        },
        computed : {
            isInputComplete(){
                const isFilled = Object.values(this.customerInformation).every(value => value.trim() !== '');

                // Validate email using a regex pattern
                const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.customerInformation.contact_person_email);

                // Validate phone number (assuming it's a 10-digit number)
                const isValidPhone = /^\d{10}$/.test(this.customerInformation.contact_person_contact_number);

                // Set error messages
                this.errors.contact_person_email= isValidEmail ? '' : 'Required';
                this.errors.contact_person_contact_number = isValidPhone ? '' : 'Phone number must be 10 digits';

                return isFilled && isValidEmail && isValidPhone;
            },
             productFilter(){
                if(this.searchItem != ''){
                    return this.productList.filter(item => item.name.toLowerCase().includes(this.searchItem.toLowerCase()));
                }
                else{
                    return this.productList;
                }
            },
            subTotal(){
                const total = this.items.reduce((total, item) => {
                const unitPrice = Number(item.unit_price) || 0; // Default to 0 if NaN
                const inputQuantity = Number(item.input_quantity) || 0; // Default to 0 if NaN
                
                return total + (unitPrice * inputQuantity);
                }, 0);

                const formattedTotal = this.items.length > 0 ? total: 0;
                return formattedTotal;
            },
            hasPayment(){
                return this.orderInfo.payment != 0  ? true : false;
            },
            grandTotal(){
                let subTotal = this.items.length > 0 ?  this.items.reduce((total, item) => total + (Number(item.unit_price) * Number(item.input_quantity)), 0).toFixed(2) : 0;
                let payment  = this.orderInfo.payment;
                let grandTotal = Number(subTotal) - Number(payment); 
                return this.formatNumber(grandTotal);
            }
        }
    }
</script>  


<style scoped>

.title-header{
    font-weight: 700;
    font-size: 14px;
    padding-bottom: 10px;
    border-bottom:  1px solid #dee2e6 ;
}

.shadow{
    box-shadow: rgba(17, 17, 26, 0.1) 0px 1px 0px;
}

</style>