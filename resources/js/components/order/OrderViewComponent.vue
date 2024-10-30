<template>
    <div>
        <div class="header mb-4">
            <h1>Order Information</h1>
            <div class="w-100 border-bottom mt-3 mb-2"></div>
        </div>

        <div class="customer-informatio">
            <div class="row">
                <div class="col-md-6">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5 style="font-weight: 600;">Business Information</h5>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="business_name"  class="info-label">BIR 3202</label>
                                <input type="text" class="form-control" v-model="orderDataInformation.customer.business_tax_id" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="business_name"  class="info-label">Business Name</label>
                                <input type="text" class="form-control" v-model="orderDataInformation.customer.business_name" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="business_name"  class="info-label">Billing Address</label>
                                <textarea name="" rows="5" id="" class="form-control" v-model="orderDataInformation.customer.billing_address" readonly ></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="business_name"  class="info-label">Shipping Address</label>
                                <textarea name="" rows="5" id="" class="form-control" v-model="orderDataInformation.customer.shipping_address" readonly> </textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5 style="font-weight: 600;">Contact Information</h5>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="business_name" class="info-label">Name</label>
                                <input type="text" class="form-control" v-model="orderDataInformation.customer.contact_person" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="business_name"  class="info-label">Contact Number</label>
                                <input type="text" class="form-control" v-model="orderDataInformation.customer.contact_person_contact_number" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="business_name"  class="info-label">Email</label>
                                <input type="text" class="form-control" v-model="orderDataInformation.customer.contact_person_email" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a  class="nav-link" :class="{ active : activeTab === 'tab1'}" aria-current="page" v-on:click="activeTab = 'tab1'">Ordered Items</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ active : activeTab === 'tab2'}" aria-current="page" v-on:click="activeTab = 'tab2'">Payment History</a>
                        </li>
                    </ul>
                    <div v-show="activeTab == 'tab1'" class="mt-2" style="height: 300px; overflow-y: scroll;">
                        <v-client-table  :data="orderDetail" :columns="item_columns" :options="item_options">
                            <template v-slot:role="{ row }">
                                <span>{{ formatRoles(row) }}</span>
                            </template>
                        </v-client-table>
                    </div>
                    <div v-show="activeTab == 'tab2'" class="mt-2" style="height: 300px; overflow-y: scroll;">
                        <div class="mt-3 mb-3 text-right">
                            <button class="btn btn-primary " v-on:click="addPayment" v-if="grandTotal != 0">Add Payment</button>
                        </div>
                        <v-client-table  :data="paymentLists" :columns="columns" :options="options">
                            <template v-slot:role="{ row }">
                                <span>{{ formatRoles(row) }}</span>
                            </template>
                        </v-client-table>
                    </div>

                    <div class="border-bottom w-100 mb-2 mt-2"></div>
                    <div class="order-overview mt-2">
                        <p class="title-header">Payment Information</p>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <p>Subtotal</p>
                            </div>
                            <div class="col-6">
                                <p class="text-right">{{ formatNumber(subTotal)  }}</p>
                            </div>
                        </div>
                        <div class="row justify-content-between" >
                            <div class="col-6">
                                <p>Total Payment</p>
                            </div>
                            <div class="col-6">
                                <p class="text-right"> {{ formatNumber(totalPayment)}}</p>
                            </div>
                        </div>
                        <div class="w-100 border-bottom mt-2 mb-2"></div>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <p class="font-weight-bold">TOTAL</p>
                            </div>
                            <div class="col-6">
                                <p class="text-right font-weight-bold">{{ formatNumber(grandTotal)}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-right">
            <button class="button close-btn mr-2" v-on:click="toogleCancel()">Cancel</button>
            <button class="button create-btn" v-on:click="generateInvoice" v-if="grandTotal != 0">Generate Invoice</button>
            <button class="btn btn-primary" v-on:click="generateFinalInvoice" v-else>Generate Final Invoice</button>
        </div>
        </div>
        
        <div class="modal fade" id="add-payment-modal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" >Add Payment Form</h5>
                        <button class="btn btn-danger" v-on:click="closeModal"><i class="fas fa-times-circle"></i></button>
                    </div>
                    <div class="modal-body" >
                        <div class="form-group">
                            <label for="payment_method">Payment Date</label>
                            <input type="date" class="form-control" v-model="orderInfo.order_date">
                            <span v-if="errors.payment_method" class="error-message" style="color: red;">{{ errors.payment_method[0]}}</span>
                        </div>
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
                        <div class="form-group">
                            <label for="payment_method">Payment Amount</label>
                            <input type="text" class="form-control" v-model="orderInfo.amount">
                            <span v-if="errors.amount" class="error-message" style="color: red;">{{ errors.amount[0]}}</span>
                        </div>
                        <div class="text-right">
                            <button class="button cancel-btn mr-2" v-on:click="closeModal">Cancel</button>
                            <button class="btn btn-success" v-on:click="update">Save</button>
                        </div>
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
            orderData :{
                type : Object,
                default : {},
            },  
            orderDetails : {
                type : Array,
                default : [],
            }
        },  
        data(){
            return {
                //default 
                loading: false,
                activeTab : 'tab1',

                //variables
                file: null,
                business_tax_file : [],
                productList : [],
                orderInfo : {
                    payment_date : '',
                    payment_method : '',
                    amount : '',
                },
                customerInformationForm : true,
                searchItem : '',
                
                items : [],
                item_columns: [ 'product_name','quantity','unit_price','total'],
                item_options: {
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
                        unit_price : function(h, row){
                            const totalValue = row.unit_price;
                            // Use Intl.NumberFormat to format as currency
                            const formattedTotal = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'PHP' // You can change this to any currency code, e.g., 'EUR'
                            }).format(totalValue);
                            
                            // Return a virtual DOM element with the formatted currency value
                            return formattedTotal
                        },
                        total: function(h, row){
                            const totalValue = row.total;
                            // Use Intl.NumberFormat to format as currency
                            const formattedTotal = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'PHP' // You can change this to any currency code, e.g., 'EUR'
                            }).format(totalValue);
                            
                            // Return a virtual DOM element with the formatted currency value
                            return formattedTotal;
                        }
                    },
                    filterable:  false, //filter change when needed
                },

                payments : [],
                columns: [ 'payment_date','amount','payment_method'],
                options: {
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
                        amount: function(h, row){
                            const totalValue = row.amount;
                            // Use Intl.NumberFormat to format as currency
                            const formattedTotal = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'PHP' // You can change this to any currency code, e.g., 'EUR'
                            }).format(totalValue);
                            
                            // Return a virtual DOM element with the formatted currency value
                            return formattedTotal;
                        }
                    },
                    filterable:  false, //filter change when needed
                },
          
            }
        },
        mounted(){
            console.log(this.orderData.payments);
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
            addPayment(){
                $('#add-payment-modal').modal('show');
            },
            closeModal(){
                $('#add-payment-modal').modal('hide');
            },
            update(){
                this.$emit('update', this.orderInfo, this.orderDataInformation.id);
                $('#add-payment-modal').modal('hide');
            },
            generateInvoice(){
                this.$emit('generateInvoice', this.orderDataInformation.id);
            },
            generateFinalInvoice(){
                this.$emit('generateFinalInvoice', this.orderDataInformation.id);
            },
            formatNumber(value) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency', // or 'decimal'
                    currency: 'PHP', // Change this if needed, e.g., 'EUR', 'GBP'
                    minimumFractionDigits: 2, // Ensures two decimal places
                }).format(value);
            },
        },
        computed : {
            orderDataInformation(){
                return this.orderData;
            },
            paymentLists(){
                return this.orderData.payments;
            },
            orderDetail(){
                return this.orderDetails  ;
            },
            subTotal(){
                const total = this.orderDetails.reduce((total, item) => {
                const unitPrice = Number(item.unit_price) || 0; // Default to 0 if NaN
                const inputQuantity = Number(item.quantity) || 0; // Default to 0 if NaN
                
                return total + (unitPrice * inputQuantity);
                }, 0);

                const formattedTotal = this.orderDetails.length > 0 ? total: 0;
                console.log(formattedTotal, "test");
                return formattedTotal;
            },
            totalPayment(){
                const total = this.paymentLists.reduce((sum, payment) => {
                    return sum + Number(payment.amount); // Add each amount to the sum
                }, 0); // 0 is the initial value of the sum
                return total;
            },
            grandTotal(){
                return Number(this.subTotal) - Number(this.totalPayment);
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

.info-label{
    color: rgb(55, 54, 54);
}

.tab-btn{
    width: 100%;
}

</style>