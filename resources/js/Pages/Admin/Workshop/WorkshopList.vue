<template>
  <AppLayoutAdmin>
    <div class="layout">
      <Sidebar />
      <div class="content workshop-list">
        <div class="page-title-area">
          <h1>ワークショップ管理</h1>
          <div class="search-form">
            <input
              type="text"
              class="form-control"
              placeholder="タイトル・キーワード・カテゴリー"
            />
            <button class="btn btn-primary" @click="goToCreate">
              新規ワークショップ
            </button>
          </div>
        </div>
        <div class="page-card">
          <table class="table workshop-table">
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
                <th>ステータス<span class="icon-sort"></span></th>
                <th>サムネイル</th>
                <th>タイトル<span class="icon-sort"></span></th>
                <th>公開状況</th>
                <th>参加者リスト</th>
                <th>編集</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(workshop,index) in workshops" :key="index">
                <td>{{ formatdate(workshop.created_at) }}</td>
                <td>
                  <div class="status accept" v-if="workshop.reception_status == 1 ">受付中</div>
                  <div class="status end" v-if="workshop.reception_status == 0">受付終了</div>
                </td>
                <td><img :src="workshop.thumbnail" alt="" /></td>
                <td>
                  <div class="border-left">
                    <p>
                      {{workshop.name}}
                    </p>
                  </div>
                </td>
                <td>
                  <button class="btn btn-link" v-if="workshop.status ==1">
                    <img :src="'/images/icon-eye-primary.svg'" alt="" />公開
                  </button>
                  <button class="btn btn-link" v-if="workshop.status ==0">
                                <img :src="'/images/icon-eye.svg'" alt="" />非公開
                            </button>
                </td>
                <td>
                  <div>
                    {{workshop.user_count_count}} / {{workshop.capacity}}
                    <button class="btn btn-primary-outline" @click="goToParticipant(workshop.id)">
                      <img :src="'/images/icon-list.svg'" alt="" />リスト
                    </button>
                  </div>
                </td>
                <td>
                  <button class="btn btn-dark" type="button" @click="goToEdit(workshop.id)">編集</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- <div class="btn-area">
                      <button class="btn btn-secondary">非公開で保存</button>
                      <button class="btn btn-primary">公開する</button>
                  </div> -->
      </div>
    </div>

    <!-- Toast -->
    <div class="toast toast-success" v-if="successToast" sty>
      <div class="toast-body">ワークショップが正常に保存されました。</div>
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
    workshops: Object,
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
      Inertia.get("/admin/workshop/create");
    },
    goToEdit(id) {
      Inertia.get("/admin/workshop/"+id+"/edit");
    },
    goToParticipant(id) {
      Inertia.get("/admin/workshop/"+id+"/participants");
    },
    formatdate(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      return formattedDate; //20171019
    },
  },
};
</script>
