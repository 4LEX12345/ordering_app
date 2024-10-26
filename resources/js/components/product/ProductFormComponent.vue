<template>
    <div>
        <div class="container mt-5">
    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
      <li class="nav-item" v-for="(step, index) in steps" :key="index">
        <a
          class="nav-link"
          :class="{ active: currentStep === index }"
          @click="goToStep(index)"
          href="#"
          role="tab"
        >
          {{ step.title }}
        </a>
      </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
      <div
        class="tab-pane fade"
        v-for="(step, index) in steps"
        :key="index"
        :class="{ show: currentStep === index, active: currentStep === index }"
      >
        <h3>{{ step.title }}</h3>
        <p>{{ step.content }}</p>
        <button
          v-if="currentStep > 0"
          class="btn btn-secondary"
          @click="prevStep"
        >
          Previous
        </button>
        <button
          v-if="currentStep < steps.length - 1"
          class="btn btn-primary"
          @click="nextStep"
        >
          Next
        </button>
        <button
          v-if="currentStep === steps.length - 1"
          class="btn btn-success"
          @click="finish"
        >
          Finish
        </button>
      </div>
    </div>
  </div>
        <div class="row">
            <div class="col-6"> 
             
                <div class="form-group">
                    <label for="product_category_name">Product Name</label>
                    <input v-model="getProduct.name" type="text"  id="first_name" :class="{'form-control': true, 'border border-danger': errors.name}">
                    <span v-if="errors.name" class="error-message">{{ errors.name[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="flavor_id">Flavor</label>
                    <select name="flavor_id" id="flavor_id" v-model="getProduct.flavor_id"  :class="{'form-control': true, 'border border-danger': errors.flavor_id}">
                        <option value="">Please Select Flavor</option>
                        <option v-for="item in flavors" :value="item.id">{{ item.name}}</option>
                    </select>
                    <span v-if="errors.flavor_id" class="error-message">{{ errors.flavor_id[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="brand_id">Brand</label>
                    <select name="brand_id" id="brand_id" v-model="getProduct.brand_id" :class="{'form-control': true, 'border border-danger': errors.brand_id}">
                        <option value="">Please Select Brand</option>
                        <option v-for="item in brands" :value="item.id">{{ item.name}}</option>
                    </select>
                    <span v-if="errors.brand_id" class="error-message">{{ errors.brand_id[0]}}</span>

                </div>
                <div class="form-group">
                    <label for="product_category">Product Category</label>
                    <select name="product_category_id" id="product_category_id" v-model="getProduct.product_category_id" :class="{'form-control': true, 'border border-danger': errors.brand_id}">
                        <option value="">Please Select Product Category</option>
                        <option v-for="item in productCategories" :value="item.id">{{ item.name}}</option>
                    </select>
                    <span v-if="errors.product_category_id" class="error-message">{{ errors.product_category_id[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="unit_price">Unit Price</label>
                    <input v-model="getProduct.unit_price" type="text"  id="unit_price" :class="{'form-control': true, 'border border-danger': errors.unit_price}" placeholder="0.00">
                    <span v-if="errors.unit_price" class="error-message">{{ errors.unit_price[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="description">Description <span style="font-style: italic; color:grey; font-size: 10px;">Please separate the desrciption by comma (,)</span></label>
                    <textarea name="description" id="description" rows="5" cols="5" v-model="getProduct.description" :class="{'form-control': true, 'border border-danger': errors.brand_id}"></textarea>
                    <span v-if="errors.description" class="error-message">{{ errors.description[0]}}</span>

                </div>
             
                <button class="btn btn-warning mr-2" v-on:click="toogleCancel()">Cancel</button>
                <button class="btn btn-primary" v-show="toggleCreate" v-on:click="store">Create Product</button>
                <button class="btn btn-primary" v-show="toggleEdit" v-on:click="update">Update Product</button>
            </div>

            <div class="col-6">
                <h5 class="text-center">Image Upload</h5>
                <div
                class="border border-dashed text-center mb-4"
                @dragover.prevent
                @drop.prevent="onDrop"
                @click="triggerFileInput"
                style="cursor: pointer; background-color: #f8f9fa;"
                >
                <input
                    type="file"
                    ref="fileInput"
                    @change="onFileChange"
                    accept="image/*"
                    class="d-none"
                />
                <p class="text-muted">Drag and drop your image here or click to upload</p>
                <p class="text-muted">(JPEG, PNG, JPG, GIF up to 2MB)</p>
                <div v-if="this.productData.image !== undefined ? getImagePreview : imagePreview" class="mt-3">
                    <img
                    :src="this.productData.image !== undefined ? getImagePreview : imagePreview"
                    alt="Image Preview"
                    class="img-fluid rounded"
                    style="max-height: 300px; object-fit: cover;"
                    />
                </div>
                </div>
                <span v-if="errors.image" class="error-message">{{ errors.image[0]}}</span>
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
            brands : {
                type : Array,
                default : [],
            },
            flavors : {
                type : Array,
                default : [],
            },
            productCategories : {
                type : Array,
                default : [],
            },
            productData : {
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
                image: null,
                imagePreview: null,
                uploadMessage: '',
                uploadError: false,
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
                this.$emit('store',this.getProduct, this.image);
            },
            update(){
                this.$emit('update',this.getProduct, this.image);
            },
            onFileChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.image = file;
                    this.productData.image = undefined;
                    this.imagePreview = URL.createObjectURL(file);
                    this.uploadMessage = ''; // Reset message on new selection
                }
            },
            onDrop(event) {
                const file = event.dataTransfer.files[0];
                if (file) {
                    this.image = file;
                    this.imagePreview = URL.createObjectURL(file);
                    this.uploadMessage = ''; // Reset message on new selection
                }
            },
            triggerFileInput() {
                this.$refs.fileInput.click(); // Access the ref correctly
            },
        },
        computed : {
            getProduct(){
                return this.productData;
            },
            getImagePreview(){
                this.imagePreview = null;
                return `/storage/${this.productData.image}`;
            },
        }
    }
</script>  


<style scoped>
.border-dashed {
  border-style: dashed;
}
</style>