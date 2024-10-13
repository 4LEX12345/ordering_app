<template>
    <div>

        <div class="header mb-4">
            <h1>Customer Information</h1>
            <div class="w-100 border-bottom mt-3 mb-2"></div>
        </div>
        <div class="row">
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
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_person_email">Contact Person Email</label>
                            <input v-model="customerInformation.contact_person_email" type="text"  id="contact_person_email" :class="{'form-control': true, 'border border-danger': errors.contact_person_email}" placeholder="example@gmail.com">
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

            <div class="col-12">
                <button class="btn btn-warning mr-2" v-on:click="toogleCancel()">Cancel</button>
                <button class="btn btn-primary" v-show="toggleCreate" v-on:click="createOrder" :disabled="!isInputComplete">Proceed to create order</button>
            </div>
        </div>

        <LoadingOverlayComponent :isVisible="loading" />
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
            toggleCreate : {
                type : Boolean,
                default : false,
            },
            toggleEdit : {
                type : Boolean,
                default : false,
            }
        },  
        data(){
            return {
                //default 
                loading: false,

                //image upload
                file: null,
                business_tax_file : [],
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
                        this.$emit('cancelCreation', false);
                    }, 1000);
                }
            },
            store(){
                this.$emit('store',this.customerInformation, this.image);
            },
            update(){
                this.$emit('update',this.customerInformation, this.image);
            },
            onFileChange(event) {
                this.business_tax_file = event.target.files[0]; // Get the selected file
                this.customerInformation.business_tax_id =   this.business_tax_file.name;
            },
         
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
            }
        }
    }
</script>  
