<template>
    <div class="p-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">System</li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">User</a></li>
            </ol>
        </nav>

      <div class="main-container p-5 pt-3 ">
        <div class="row mb-2">
        <div class="col-md-12">
          <div class="">
            <button class="custom-add-btn" v-on:click="create"><i class="fas fa-solid fa-plus"></i></button>
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
                <button class="btn" v-on:click="changePasssword(row)"><i class="fas fa-user-shield text-warning"></i></button>
                <button class="btn" v-on:click="destroy(row)"><i class="fas fa-trash text-danger"></i></button>
              </template>
            </v-client-table>
        </div> 

        <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" >Add User</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input v-model="user.name" type="text"  id="first_name" :class="{'form-control': true, 'border border-danger': errors.name}">
                            <span v-if="errors.name" class="error-message">{{ errors.name[0]}}</span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input v-model="user.email" type="text" id="email" :class="{'form-control': true, 'border border-danger': errors.email}">
                            <span v-if="errors.email" class="error-message">{{ errors.email[0]}}</span>
                        </div>
                        <div class="form-group"  v-if="!isEdit">
                            <label for="password">Password</label>
                            <input v-model="user.password" type="password" id="password" :class="{'form-control': true, 'border border-danger': errors.password}">
                            <span v-if="errors.password" class="error-message">{{ errors.password[0]}}</span>
                        </div>
                        <div class="form-group"  v-if="!isEdit">
                            <label for="password_confirmation">Confirm Password</label>
                            <input v-model="user.password_confirmation" type="password"  id="password_confirmation" :class="{'form-control': true, 'border border-danger': errors.password_confirmation}">
                            <span v-if="errors.password_confirmation" class="error-message">{{ errors.password_confirmation[0]}}</span>

                        </div>
                        <div class="form-group" >
                            <label for="stall_id">Role</label>
                            <select id="dropdown"  v-model="user.role_id" :class="{'form-control': true, 'border border-danger': errors.role_id}">
                                <option value="">Select Role</option>
                                <option v-for="item in roleLists" :value="item.id">{{item.name}}</option>
                            </select>
                            <span v-if="errors.role_id" class="error-message">{{ errors.role_id[0]}}</span>

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
        <div class="modal fade" id="change-pass-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" >Change Password</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" >
                            <label for="password">Password</label>
                            <input v-model="user.password" type="password" class="form-control" id="password_edit">
                        </div>
                        <div class="form-group" >
                            <label for="password_confirmation">Confirm Password</label>
                            <input v-model="user.password_confirmation" type="password" class="form-control" id="password_confirmation_edit">
                        </div>
                    
                      
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button @click="saveChangePassword" type="button" class="btn btn-primary">Save changes</button>
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
    props : ['roles'],
    components:{LoadingOverlayComponent},
    data() {
      return {
        loading: false,
        isEdit : false,
        btnAction : '',
        errors : [],
        btnActionitem : [],
        roleLists : [],
        user : [],

        tableData:[],
        columns: ['id', 'name', 'email', 'role', 'actions'],
        options: {
            pagination : {
              virtual : true,
            },
            // see the options API
            filterable:  false,
        }
      };
    },
    methods: {
      init(){
        this.roleLists = this.roles;
        this.user.role_id = '';
      },
      getData(){
        axios.get('user/getdata').then(response => 
          {
            this.tableData = response.data.users;
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
      formatRoles(row) {
        console.log(row.roles.length);
        if (row.roles.length == 0) {
          return 'No Role';
        }
        else{
          return row.roles.map(role => role.name).join(', ');
        }
      },
      create(){
        this.user = [];
        this.isEdit = false;
        $('#create-modal').modal('show');
      },
      store(){
        this.loading = true;
        axios.post('user/store', {
            name : this.user.name,
            email : this.user.email,
            password : this.user.password,
            password_confirmation : this.user.password_confirmation,
            role_id : this.user.role_id
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
        this.user.id = row.id;
        this.user.name = row.name;
        this.user.email = row.email;
        this.user.role_id =row.roles.length == 0 ?  '' : row.roles[0]['id'];
        $('#create-modal').modal('show');
      },
      update(){
        this.loading = true;
        axios.post('user/update', {
            id : this.user.id,
            name : this.user.name,
            email : this.user.email,
            password : this.user.password,
            password_confirmation : this.user.password_confirmation,
            role_id : this.user.role_id
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
            axios.get('user/destroy/' + row.id).then(response => {
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
      changePasssword(row){
        this.user = [];
        this.user.id = row.id;
        $('#change-pass-modal').modal('show');
      },
      async saveChangePassword(){
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will change this user password",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
          });

          if(result.isConfirmed){
            this.loading = true;
            axios.post('user/changepassword' ,{
              id : this.user.id,
              password : this.user.password,
              password_confirmation : this.user.password_confirmation,
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
              $('#change-pass-modal').modal('hide');
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
      }
  
    },
    mounted() {
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
  