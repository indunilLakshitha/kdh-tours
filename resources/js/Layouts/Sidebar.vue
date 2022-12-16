<template>
  <ul class="sidebar">
    <li v-for="(menu, index) in side_menu" :key="index">
      <Link :href="menu.url">{{ menu.name }}</Link>
      <!-- <Link href="/admin/dashboard">TOP</Link> -->
    </li>
  </ul>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Link,
  },
  data() {
    return {
      side_menu: [
        {
          name: "Q n A Sec",
          url: "/admin/qna",
        },
        {
          name: "How It Works Sec",
          url: "/admin/how",
        },
        {
          name: "Top Count Sec",
          url: "/admin/top",
        },
        {
          name: "Why Us Sec",
          url: "",
        },
        {
          name: "Destinations",
          url: "",
        },
        {
          name: "Gallery",
          url: "",
        },
        {
          name: "Reviews",
          url: "",
        },
      ],
    };
  },
};
</script>
