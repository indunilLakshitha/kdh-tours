<template>
  <AppLayoutAdmin>
    <div class="layout">
      <Sidebar />
      <div class="content learning-path-list">
        <div class="page-title-area">
          <h1>ラーニングパス一覧</h1>
          <div class="search-form">
            <input type="text" class="form-control" placeholder="検索" />
            <button class="btn btn-dark">複製</button>
            <button class="btn btn-primary" @click="goToCreate()">新規作成</button>
          </div>
        </div>
        <div class="page-card">
          <div class="">
            <table class="table learning-path-table">
              <thead>
                <tr>
                  <td colspan="7">
                    <div class="icon-filter">
                      <img :src="'/images/icon-filter-list.svg'" alt="" />フィルタ
                    </div>
                  </td>
                </tr>
                <tr>
                  <th>選択</th>
                  <th>日付<span class="icon-sort"></span></th>
                  <th>サムネイル</th>
                  <th>カテゴリー<span class="icon-sort"></span></th>
                  <th>タイトル<span class="icon-sort"></span></th>
                  <th>企業名</th>
                  <th>公開状況</th>
                </tr>
              </thead>
              <tbody>
                
                <tr v-for="(path,index) in paths" :key="index">
                  <td><input type="checkbox" class="form-check" /></td>
                  <td>{{formatdate(path.created_at)}}</td>
                  <td><img :src="path.thumbnail" alt="" /></td>
                  <td>{{path.category.category.name}}</td>
                  <td>
                    <div class="border-left">
                      <p>
                        {{path.name}}
                      </p>
                    </div>
                  </td>
                  <td v-if="path.company_type == 1">ALL</td>
                  <td>
                    <button class="btn btn-link" v-if="path.status == 1">
                      <img :src="'/images/icon-eye-primary.svg'" alt="" />公開
                    </button>
                    <button class="btn btn-link" v-if="path.status != 1">
                        <img :src="'/images/icon-eye.svg'" alt="" />非公開
                      </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
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
  props: { msg: String, paths: Object },
  data() {
    return {
      successToast: false,
    };
  },
  methods: {
    goToCreate() {
      Inertia.get("/admin/learning-path/create");
    },
    goToEdit(id) {
      Inertia.get("/admin/workshop/" + id + "/edit");
    },
    goToParticipant(id) {
      Inertia.get("/admin/workshop/" + id + "/participants");
    },
    formatdate(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      return formattedDate; //20171019
    },
  },
  created() {
    if (this.msg == "S") {
      this.successToast = true;
      setTimeout(() => (this.successToast = false), 3000)
    }
  },
};
</script>
