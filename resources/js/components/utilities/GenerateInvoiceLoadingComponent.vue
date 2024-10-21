<template>
  <div v-if="loading" class="loading-container">
    <h2>Generating invoice, please wait...</h2>
    <div class="progress-bar">
      <div class="progress-bar-fill" :style="{ width: progress + '%' }"></div>
    </div>
    <p>{{ progress }}%</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: true,
      progress: 0,
    };
  },
  mounted() {
    this.simulateProgress();
  },
  methods: {
    simulateProgress() {
      const interval = setInterval(() => {
        if (this.progress < 100) {
          this.progress += 1;
        } else {
          clearInterval(interval);
          this.loading = false; // Hide the loading screen after completion
        }
      }, 50); // Adjust speed as needed
    },
  },
};
</script>

<style scoped>
.loading-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f8f9fa;
  text-align: center;
}

.progress-bar {
  width: 80%;
  height: 25px;
  background-color: #ddd;
  border-radius: 12.5px;
  margin: 20px 0;
}

.progress-bar-fill {
  height: 100%;
  background-color: #28a745;
  border-radius: 12.5px;
  transition: width 0.2s ease;
}
</style>
