<template>
  <div class="p-3">
    <div class="row justify-content-between">
      <div class="col-md-6">
        <div class="main-container p-5 mb-4">
          <BarChart :data="data" :options="options" />
        </div>
      </div>
      <div class="col-md-6">
        <div class="main-container p-5 mb-4" >
          <LineChart :data="Linedata" :options="Lineoptions" />
        </div>  
      </div>
      <div class="col-md-6">
        <div class="main-container p-5 mb-4" >
          <PieChart :data="Piedata" :options="Pieoptions" />

        </div>  
      </div>
      <div class="col-md-6">
        <div class="main-container p-5 mb-4" >
          <DoughnutChart :data="Doughnutdata" :options="Doughnutoptions" />
        </div>  
      </div>
    </div>
   
  </div>
</template>

<script>


import {Chart as ChartJS,Title,Tooltip,Legend,BarElement,CategoryScale,LinearScale,PointElement, LineElement, ArcElement} from 'chart.js';
import { Bar, Line, Pie, Doughnut } from 'vue-chartjs';
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, PointElement, LineElement, ArcElement);

export default {
  components: {
    BarChart : Bar,
    LineChart: Line,
    PieChart : Pie,
    DoughnutChart : Doughnut,
  },
  data() {
    return {
      data: {
        labels: ['January', 'February', 'March'],
        datasets: [{ data: [40, 20, 12] }]
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

  },
  mounted() {
  }
}
</script>

<style scoped>
  .main-container{
    box-shadow: rgba(27, 31, 35, 0.04) 0px 1px 0px, rgba(255, 255, 255, 0.25) 0px 1px 0px inset;
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
