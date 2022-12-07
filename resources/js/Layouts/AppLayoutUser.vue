<template>
  <main>
    <Header />
    <!-- <div class="user-layout"> -->
    <div>
      <slot />
    </div>
    <Footer />
  </main>
</template>

<script>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Header from "./User/Header.vue";
import Footer from "./User/Footer.vue";

export default {
  components: { Header, Footer, Inertia },
};
</script>
