<template>
    <div class="p-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Masterdata</li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Assign Role</a></li>
            </ol>
        </nav>

      <div class="main-container p-2 pt-3">
        <div class="row">
          <div class="col-md-6">
          </div>
        <div class="col-md-6 ">
          <div class="row justify-content-end">
            <button class="custom-add-btn">Create Order</button>
          </div>
        </div>
        </div>

        <div id="people">
                <v-client-table :data="tableData" :columns="columns" :options="options"/>
            </div>
      
    
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            {{ btnActionitem}}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Understood</button>
          </div>
        </div>
      </div>
    </div>
      </div>
     
  
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        btnAction : '',
        btnActionitem : [],
        tableData: this.generateMockData(),
        columns: ['id', 'name', 'email'],
        options: {
            // see the options API
            filterable:  false,
        }
      };
    },
    methods: {
      generateMockData() {
        const data = [];
        for (let i = 1; i <= 100; i++) { // Generate 100 rows of data
          data.push({
            id: i,
            name: `Name ${i}`,
            email: `name${i}@example.com`,
          });
        }
        return data;
      },
      handleBtnAction({ action, item }) {
        this.btnAction = action.name;
        this.btnActionitem = item;
        $('#staticBackdrop').modal('show');
      },
  
    },
    mounted() {
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
      border-radius: 5px;
      box-shadow: rgba(14, 63, 126, 0.06) 0px 0px 0px 1px, rgba(42, 51, 70, 0.03) 0px 1px 1px -0.5px, rgba(42, 51, 70, 0.04) 0px 2px 2px -1px, rgba(42, 51, 70, 0.04) 0px 3px 3px -1.5px, rgba(42, 51, 70, 0.03) 0px 5px 5px -2.5px, rgba(42, 51, 70, 0.03) 0px 10px 10px -5px, rgba(42, 51, 70, 0.03) 0px 24px 24px -8px;
      padding: 5px;
      background-color: #28a745;
      color: white;
      width: 200px;
      margin-right: 20px;
    }
  </style>
  