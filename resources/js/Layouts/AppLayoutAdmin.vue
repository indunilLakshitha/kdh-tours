<template>
  <main>
    <AdminHeader />
    <div class="layout">
      <Sidebar />
      <slot />
    </div>
    <AdminFooter />
  </main>
</template>


<script >
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import AdminHeader from "./AdminHeader.vue";
import Sidebar from "./Sidebar.vue";
import AdminFooter from "./AdminFooter.vue";

export default {
    components: { AdminHeader, Sidebar, AdminFooter },
}

</script>