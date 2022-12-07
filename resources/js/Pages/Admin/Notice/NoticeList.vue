<template>
  <AppLayoutAdmin>
    <div class="content notice-list">
      <div class="page-title-area">
        <h1>お知らせ</h1>
        <div class="search-form">
          <button class="btn btn-secondary" @click="removeNotices">削除</button>
          <button class="btn btn-primary" @click="goToCreate">新規講座</button>
        </div>
      </div>
      <div class="page-card">
        <table class="table notice-table">
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
              <th>カテゴリー<span class="icon-sort"></span></th>
              <th>タイトル<span class="icon-sort"></span></th>
              <th>投稿者</th>
              <th>公開状況</th>
              <th>編集</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(notice, index) in notices" :key="index">
              <td>
                <input
                  type="checkbox"
                  class="form-check sml"
                  name=""
                  @change="selectNotice($event, notice.id)"
                />
              </td>
              <td>{{ formatdate(notice.created_at) }}</td>
              <td>{{ notice.categories[0].category.category }}</td>
              <td>
                <div class="border-left">
                  <p>
                    {{ notice.title }}
                  </p>
                </div>
              </td>
              <td>{{ notice.user.username }}</td>
              <td>
                <button class="btn btn-link" v-if="notice.status == 1">
                  <img :src="'/images/icon-eye-close.svg'" alt="" />非公開
                </button>
                <button class="btn btn-link" v-if="notice.status == 2">
                  <img :src="'/images/icon-eye-primary.svg'" alt="" />公開
                </button>
              </td>
              <td><button class="btn btn-dark" type="button" @click="goToEdit(notice.id)">編集</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Toast -->
    <div class="toast toast-success" v-if="successToast" sty>
      <div class="toast-body">お知らせが保存されました。</div>
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
import { useForm } from "@inertiajs/inertia-vue3";

export default {
  props: {
    notices: Object,
    success: Object,
  },
  components: { AppLayoutAdmin },
  // Modal
  data() {
    return {
      open: false,
      form: useForm({
        selected_notices: [],
      }),
      notice_data: null,
      successToast: false,
    };
  },
  created() {
    // this.notice_data = this.notices;
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
      Inertia.get("/admin/notice/create");
    },
    formatdate(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      return formattedDate; //20171019
    },
    selectNotice(event, notice_id) {
      let new_selected_notices = [];
      if (this.form.selected_notices.includes(notice_id)) {
        this.form.selected_notices.forEach((e) => {
          if (e != notice_id) {
            new_selected_notices.push(e);
          }
        });

        this.form.selected_notices = new_selected_notices;
      } else {
        this.form.selected_notices.push(notice_id);
      }
    },
    removeNotices() {
      if (this.form.selected_notices.length > 0) {
        this.form.post(route("admin.notice.deleteNotices"), {
          onSuccess: () => {
            this.form.selected_notices = [];
            location.reload();
          },
          onFinish: () => {
             this.form.selected_notices = [];
             location.reload();
          },
        });
      }
    },
    goToEdit(id) {
      var url = "/admin/notice/" + id + "/edit";
      Inertia.get(url);
    },
  },
};
</script>
