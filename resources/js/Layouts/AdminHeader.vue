<template>
    <nav class="header admin-header">
        <img :src="'/images/logo.png'" alt="" class="logo" />
        <div class="admin-title">Management screen</div>
        <div class="btn-area">
            <button class="btn btn-light sml logout" @click="logoutAdmin">ログアウト</button>
            <button class="btn btn-light sml account">
                <img :src="'/images/icon-user-circle.svg'" alt="" class="logo" />
                <div>
                    <span>〇〇〇〇〇〇〇〇</span>
                    <small>でログイン</small>
                </div>
            </button>
        </div>
    </nav>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

export default {
  components: {
    Link
  },
  setup() {
    const form = {
      email: null,
      password: null,
    };

    function logoutAdmin() {
      Inertia.get(route("admin.logout"));
    }

    return { form, logoutAdmin };
  },
};
</script>