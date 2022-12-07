<template>
  <nav class="header user-header">
    <Link :href="route('user.home')" class="menu-item"
      ><img :src="'/images/logo.png'" alt="" class="logo"
    /></Link>
    <div class="menu-area">
      <!-- <a href="" class="menu-item">お気に入り</a> -->
      <Link href="/favourites" class="menu-item">お気に入り</Link>
      <Link href="/category" class="menu-item">講座検索</Link>
      <Link href="/workshop" class="menu-item">ワークショップ</Link>
    </div>
    <div class="btn-area">
      <!-- <button class="btn btn-light sml logout">ログアウト</button> -->
      <button class="btn btn-light sml account" @click="goToMyPage">
        <img :src="'/images/icon-user-circle.svg'" alt="" />
        <div>マイアカウント</div>
      </button>
    </div>
  </nav>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

export default {
  components: {
    Link,
    Inertia,
  },

  methods: {
    goToMyPage() {
      Inertia.get("/my-page");
    },
  },
};
</script>
