src/components/LoadingOverlay.vue
<template>
    <div v-if="isVisible" class="loading-overlay">
        <div class="spinner-grow text-primary" role="status">
        <span class="sr-only">Loading...</span>
        </div>
        <div class="spinner-grow text-success" role="status">
        <span class="sr-only">Loading...</span>
        </div>
        <div class="spinner-grow text-danger" role="status">
        <span class="sr-only">Loading...</span>
        </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      isVisible: {
        type: Boolean,
        default: false
      }
    }
  }
  </script>
  
  <style scoped>
  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
  }
  
  .spinner {
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    border-top: 4px solid #ffffff;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  </style>
  