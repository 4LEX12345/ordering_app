src/components/LoadingOverlay.vue
<template>
    <div v-if="isVisible" class="loading-overlay">
        <div class="loading-bar">Loading</div>
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
.loading-bar {
  position: relative;
  width: 110px;
  height: 110px;
  background: rgba(0, 0, 0, 0.5);
  border: px solid #3c3c3c;
  border-radius: 50%;
  text-align: center;
  line-height: 111px;
  font-family: sans-serif;
  font-size: 15px;
  color: #00BCD4;
  letter-spacing: 3px;
  text-transform: uppercase;
  text-shadow: 0 0 20px #00BCD4;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
}

.loading-bar:before {
  content: "";
  position: absolute;
  top: -3px;
  left: -3px;
  width: 100%;
  height: 100%;
  border: 3px solid transparent;
  border-top: 5px solid #00BCD4;
  border-right: 5px solid #00BCD4;
  border-radius: 50%;
  animation: animateC 2s linear infinite;
}

@keyframes animateC {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

@keyframes animate {
  0% {
    transform: rotate(45deg);
  }

  100% {
    transform: rotate(405deg);
  }
}

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
  