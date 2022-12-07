<template>
  <ul class="sidebar">
    <li>
      <Link href="/admin/dashboard">TOP</Link>
      <!-- <Link href="/admin/dashboard">TOP</Link> -->
    </li>
    <li>
      <Link href="/admin/learning-path">ラーニングパス</Link>
    </li>
    <li>
      <a href="/admin/licence-keys">ライセンスキー管理</a>
    </li>
    <li class="">
      <Link href="/admin/course">講座管理</Link>
    </li>
    <!-- <li class="active">
      <Link  href="">講座管理</Link >
    </li> -->
    <li>
       <Link href="/admin/video">動画管理</Link>
    </li>
    <li>
       <Link href="/admin/workshop/">ワークショップ管理</Link>
      <!-- <a href="">ワークショップ管理</a> -->
    </li>
    <li>
      <Link href="/admin/notice">お知らせ</Link>
    </li>
    <li>
      <!-- <Link href="/admin/logout">利用状況（ログ）</Link> -->
      <a href="" >利用状況（ログ）</a>
    </li>
  </ul>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Link
  },
  setup() {


    // function logoutAdmin() {
    //   Inertia.get(route("admin.logout"));
    // }

    // return { form, logoutAdmin };
  },
};
</script>
