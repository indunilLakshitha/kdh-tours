<template>
  <AppLayoutAdmin>
    <div class="layout">
      <Sidebar />
      <div class="content course-list">
        <div class="page-title-area">
          <h1>Destinations</h1>
          <div class="search-form">
            <input
              type="text"
              class="form-control"
              placeholder="タイトル・キーワード・カテゴリー"
            />
            <button class="btn btn-primary" @click="goToCreate">ADD</button>
          </div>
        </div>
        <div class="page-card">
          <div class="">
            <table class="table course-list">
              <thead>
             
                <tr>
                  <th><span class="icon-sort"></span></th>
                  <th>Image</th>
                  <th>Title<span class="icon-sort"></span></th>
                  <th>Status<span class="icon-sort"></span></th>
                  <th>Action</th>
           
                </tr>
              </thead>
              <tbody>
                <tr v-for="(course, index) in destinations" :key="index">
                  <td>
                    {{ formatdate(course.created_at) }}
                  </td>
                  <td>
                    <img :src="course.image_1" alt="" />
                  </td>
                  
                  <td>
                    <div class="border-left">
                      <p :title="course.title">
                        {{ course.title }}
                      </p>
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
                  <td>
                    <button
                      class="btn btn-dark"
                      type="button"
                      @click="goToEdit(course.id)"
                    >
                      EDIT
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
    destinations: Object,
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
      Inertia.get("/admin/destination/create");
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
