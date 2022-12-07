<template>
    <main>
      <HeaderWatch />
      <!-- <div class="user-layout"> -->
      <div>
        <slot />
      </div>
      <Footer />
    </main>
  </template>
  
  
  <script >
  import { ref } from "vue";
  import { Inertia } from "@inertiajs/inertia";
  import HeaderWatch from './User/HeaderWatch.vue';
  import Footer from './User/Footer.vue';
  
  export default {
      components: { HeaderWatch, Footer },
  }
  
  </script>