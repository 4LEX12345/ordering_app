<template>
  <div class="p-3">
    <div class="">
      <!-- Key Metrics -->
      <div class="row mb-3 mt-3">
        <div class="col-md-4 dashboard-overview">
          <div class="card p-5 card-one">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h3>Total Orders</h3>
                <p>{{ totalOrder }}</p>
              </div>
              <div class="icon-container icon-one">
                <i class="fas fa-clipboard-list"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 dashboard-overview">
          <div class="card p-5 card-two">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h3>Pending Orders</h3>
                <p>{{ totalPending }}</p>
              </div>
              <div class="icon-container icon-two">
                <i class="fas fa-hourglass-half"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 dashboard-overview">
          <div class="card p-5 card-three">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h3>Completed Orders</h3>
                <p>{{ totalComplete }}</p>
              </div>
              <div class="icon-container icon-three">
                <i class="fas fa-clipboard-check"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card p-5 dashboard-graphs">
        <h3>Order Overview</h3>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="card dashboard-graphs p-5">
            <h3>Top Selling Products</h3>
            <ul class="list-group list-group-flush mt-3">
              <li class="list-group-item d-flex align-items-center" v-for="item in topProducts">
                <img :src="'/storage/' + item.product.image" class="rounded-circle mr-3" alt="Product A" width="90">
                <div>
                  <h5 class="mb-1">{{ item.product.name}}</h5>
                  <small class="text-muted">Brand : {{ item.product.brand.name}}</small>
                  <br>
                  <small class="text-muted">Flavor : {{ item.product.flavor.name}}</small>
                  <br>
                  <small class="text-muted">Total Orders : {{ item.product_count}}</small>
                </div>
              </li>
            </ul>
          </div>
          <!-- Top Customers -->
          <div class="card dashboard-graphs p-5">
            <h3>Top Customer</h3>
            <ul class="list-group list-group-flush mt-3">
              <li class="list-group-item d-flex align-items-center" v-for="item in topProducts">
                <img :src="'/storage/' + item.product.image" class="rounded-circle mr-3" alt="Product A" width="90">
                <div>
                  <h5 class="mb-1">{{ item.product.name}}</h5>
                  <small class="text-muted">Brand : {{ item.product.brand.name}}</small>
                  <br>
                  <small class="text-muted">Flavor : {{ item.product.flavor.name}}</small>
                  <br>
                  <small class="text-muted">Total Orders : {{ item.product_count}}</small>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card p-5 dashboard-graphs">
            <div class="">
              <h3>Orders Per Month</h3>
              <BarChart :data="data" :options="options" />
            </div>
          </div>
          <div class="card p-5">
            <div class="">
              <DoughnutChart :data="Doughnutdata" :options="Doughnutoptions" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <LoadingOverlayComponent :isVisible="loading" />
  </div>
</template>
<script>
  import axios from 'axios';
  import {
    toast
  } from 'vue3-toastify';
  import Swal from 'sweetalert2';
  import LoadingOverlayComponent from './utilities/LoadingOverlayComponent.vue';
  import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    ArcElement
  } from 'chart.js';
  import {
    Bar,
    Line,
    Pie,
    Doughnut
  } from 'vue-chartjs';
  ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, PointElement, LineElement, ArcElement);
  export default {
    props: ['collection_month'],
    components: {
      BarChart: Bar,
      LineChart: Line,
      PieChart: Pie,
      DoughnutChart: Doughnut,
      LoadingOverlayComponent,
    },
    data() {
      return {
        loading: false,
        orders: [],
        topProducts: [],
        data: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'Ocotber', 'November', 'December'],
          datasets: [{
            data: this.collection_month,
            label: 'Total Orders',
            backgroundColor: '#00BCD4',
          }]
        },
        options: {
          responsive: true
        },
        Linedata: {
          labels: ['January', 'February', 'March', 'April', 'May'],
          datasets: [{
            label: 'Sales',
            borderColor: '#f87979',
            data: [40, 20, 30, 50, 60],
          }]
        },
        Lineoptions: {
          responsive: true,
        },
        Piedata: {
          labels: ['Red', 'Blue', 'Yellow'],
          datasets: [{
            backgroundColor: ['#f87979', '#3498db', '#f1c40f'],
            data: [30, 50, 20]
          }]
        },
        Pieoptions: {
          responsive: true,
        },
        Doughnutdata: {
          labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple'], // Labels for each section of the doughnut chart
          datasets: [{
            label: 'Colors Distribution', // A label for the dataset
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'], // Colors for each section
            data: [30, 50, 20, 40, 10], // Values for each section
          }]
        },
        Doughnutoptions: {
          responsive: true, // Ensure the chart resizes with the container
          plugins: {
            legend: {
              position: 'top', // Position of the legend
            },
            title: {
              display: true,
              text: 'Sample Doughnut Chart', // Title of the chart
            },
          },
        },
      };
    },
    methods: {
      async fetchData() {
        this.loading = true;
        await axios.get('dashboard/fetchdata').then(response => {
          this.orders = response.data.orders;
          this.topProducts = response.data.top_products;
        }).catch(errors => {
          toast.success(errors.response.data.message, {
            position: toast.POSITION.TOP_RIGHT,
            autoClose: 3000,
            hideProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
          });
        }).finally(() => {
          this.loading = false
        });
      }
    },
    mounted() {
      this.fetchData();
      console.log(this.data.datasets);
    },
    computed: {
      totalOrder() {
        return this.orders.length;
      },
      totalPending() {
        return this.orders.filter(item => item.payment_status == 'pending').length;
      },
      totalComplete() {
        return this.orders.filter(item => item.payment_status == 'paid').length;
      }
    }
  }
</script>
<style scoped>
  .card {
    background: #fff;
    padding: 1.5em;
    margin-bottom: 1em;
    border-radius: 12px;
    /* Adding border radius */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
  }

  .card:hover {
    transform: translateY(-5px);
  }

  .card h2 {
    margin-top: 0;
    color: #007bff;
  }

  .chart {
    width: 100%;
    height: 300px;
    background: #e9ecef;
    border-radius: 12px;
    /* Adding border radius */
  }

  .table {
    width: 100%;
    margin-top: 1em;
    border-collapse: collapse;
    border-radius: 12px;
    /* Adding border radius */
    overflow: hidden;
  }

  .table th,
  .table td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
  }

  .table th {
    background: #007bff;
    color: #fff;
  }

  .table tbody tr:nth-child(even) {
    background: #f2f2f2;
  }

  .alert {
    padding: 15px;
    background-color: #f44336;
    color: white;
    margin-bottom: 1em;
    border-radius: 12px;
    /* Adding border radius */
  }

  .alert.success {
    background-color: #4CAF50;
  }

  .metric {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #e3f2fd;
    padding: 1em;
    border-radius: 12px;
    /* Adding border radius */
    margin-bottom: 1em;
  }

  .metric div {
    text-align: center;
  }

  .metric div h3 {
    margin: 0;
    color: #007bff;
  }

  .metric div p {
    margin: 0;
    font-size: 1.2em;
  }

  .dashboard-overview h3,
  .dashboard-graphs h3 {
    font-size: 2em;
    color: rgb(131, 131, 131);
    font-weight: 700;
  }

  .dashboard-overview p {
    font-size: 3em;
    font-weight: 600;
  }

  .icon-container p {
    margin: 0;
    font-size: 1.5em;
  }

  .icon-container i {
    font-size: 4em;
    margin-right: 10px;
  }

  .icon-one {
    color: #00BCD4;
  }

  .icon-two {
    color: #fb8c00;
  }

  .icon-three {
    color: #4CAF50;
  }

  .dashboard-overview .card-one {
    border-left: 10px solid #00BCD4;
  }

  .dashboard-overview .card-two {
    border-left: 10px solid #fb8c00;
  }

  .dashboard-overview .card-three {
    border-left: 10px solid #4CAF50;
  }

  .dashboard-graphs h5 {
    font-size: 1.5rem;
    font-weight: 500;
  }
</style>