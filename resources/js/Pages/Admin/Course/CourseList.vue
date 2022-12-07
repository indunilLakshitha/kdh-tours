<template>
  <AppLayoutAdmin>
    <div class="layout">
      <Sidebar />
      <div class="content course-list">
        <div class="page-title-area">
          <h1>講座管理</h1>
          <div class="search-form">
            <input
              type="text"
              class="form-control"
              placeholder="タイトル・キーワード・カテゴリー"
            />
            <button class="btn btn-primary" @click="goToCreate">新規登録</button>
          </div>
        </div>
        <div class="page-card">
          <div class="">
            <table class="table course-list">
              <thead>
                <tr>
                  <td colspan="7">
                    <div class="icon-filter">
                      <img :src="'/images/icon-filter-list.svg'" alt="" />フィルタ
                    </div>
                  </td>
                </tr>
                <tr>
                  <th>日付<span class="icon-sort"></span></th>
                  <th>サムネイル</th>
                  <th>カテゴリー<span class="icon-sort"></span></th>
                  <th>タイトル<span class="icon-sort"></span></th>
                  <th>公開状況</th>
                  <th>レポート</th>
                  <th>編集</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(course, index) in courses" :key="index">
                  <td>
                    {{ formatdate(course.created_at) }}
                  </td>
                  <!-- <td><img :src="'/images/course-thumb.png'" alt="" /></td> -->
                  <td>
                    <img :src="course.thumbnail" alt="" />
                  </td>
                  <td v-if="course.course_has_categories.length > 0">
                    {{ course.course_has_categories[0].category.name }}
                  </td>
                  <td v-if="course.course_has_categories.length == 0"></td>
                  <td>
                    <div class="border-left">
                      <!-- <div @mouseover="upHere = true" @mouseleave="upHere = false" > -->
                      <p :title="course.title">
                        {{ course.title }}
                      </p>
                      <!-- <div v-show="upHere">{{ course.title }}</div>
                      </div> -->
                    </div>
                  </td>
                  <td>
                    <button class="btn btn-link" v-if="course.status === 1">
                      <img :src="'/images/icon-eye-primary.svg'" alt="" />公開
                    </button>
                    <button class="btn btn-link" v-if="course.status === 0">
                      <img :src="'/images/icon-eye.svg'" alt="" />非公開
                    </button>
                  </td>
                  <td v-if="course.total_report_count > 0">あり</td>
                  <td v-if="course.total_report_count === 0">無し</td>
                  <td>
                    <button
                      class="btn btn-dark"
                      type="button"
                      @click="goToEdit(course.id)"
                    >
                      編集
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- <div class="btn-area">
                    <button class="btn btn-secondary">非公開で保存</button>
                    <button class="btn btn-primary">公開する</button>
                </div> -->
      </div>
    </div>

    <!-- Toast -->
    <div class="toast toast-success" v-if="successToast" sty>
      <div class="toast-body">講座が保存されました。</div>
      <img
        :src="'/images/icon-toast-close.svg'"
        class="btn-close"
        @click="this.successToast = false"
      />
    </div>
    <!--  -->
  </AppLayoutAdmin>
</template>

<script>
import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin.vue";
import { Inertia } from "@inertiajs/inertia";
import moment from "moment";

export default {
  components: { AppLayoutAdmin, moment },
  props: {
    courses: Object,
    success: Object,
  },
  // Modal
  data() {
    return {
      upHere: false,
      open: false,
      successToast: false,
    };
  },
  created() {
    let urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("success")) {
      if (urlParams.get("success") == 1) {
        this.successToast = true;
        setTimeout(() => {
          this.successToast = false;
        }, 5000);
      }
    }
  },
  methods: {
    goToCreate() {
      Inertia.get("/admin/course/create");
    },
    goToEdit(id) {
      var url = "/admin/course/" + id + "/edit";
      Inertia.get(url);
    },
    formatdate(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      return formattedDate; //20171019
    },
  },
};
</script>
