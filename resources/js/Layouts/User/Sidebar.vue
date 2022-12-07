<template>
  <ul class="sidebar-user">
    <li class="no-hover">
      <Link href="">
        <div>{{ $page.props.auth.user.name }}さん</div>
        <div>ダッシュボード</div>
      </Link>
    </li>
    <li>
      <Link href="/HR/attendance-status">受講状況</Link>
    </li>
    <li>
      <Link href="/profile">ユーザー設定</Link>
    </li>
    <li class="">
      <!-- Please use for active menu item -> class="active" -->
      <Link href="/course/history">受講履歴</Link>
    </li>
      <li>
        <Link href="#">契約情報</Link>
      </li>
    <li>
      <Link href="/logout">ログアウト</Link>
    </li>
    <!-- HR Manager -->
<!-- =====
    <li class="no-hover">
      <Link href="">&nbsp;</Link>
    </li>
    <li class="active">
      <Link href="">管理者</Link>
    </li>
    <li>
      <Link href="">契約状況一覧</Link>
    </li>
    <li>
      <Link href="">ラーニングパス管理</Link>
    </li>
    <li>
      <Link href="">ワークショップ管理</Link>
    </li>
    <li>
      <Link href="">学習プラン</Link>
    </li>
===== -->
    <div v-if="$page.props.auth.user.role == 1">
      <li class="no-hover no-bg">
        <Link href="">&nbsp;</Link>
      </li>
      <li class="active no-hover">
        <Link href="">管理者</Link>
      </li>
      <li>
        <Link href="/agreement-information-list">契約状況一覧</Link>
      </li>
      <li>
        <Link href="">ラーニングパス管理</Link>
      </li>
      <li>
        <Link :href="route('workshop.hr.list')">ワークショップ管理</Link>
      </li>
      <li>
        <Link href="">学習プラン</Link>
      </li>
    </div>
  </ul>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  props: {
    user: Object,
    username: String,
  },
  components: {
    Link,
  },
  mounted() {},
};
</script>
