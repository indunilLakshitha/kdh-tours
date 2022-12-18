<template>
  <AppLayoutAdmin>
    <div class="layout">
      <Sidebar />
      <div class="content learning-path-list">
        <div class="page-title-area">
          <h1>Top Count Section</h1>
          <div class="search-form">
            <!-- <input type="text" class="form-control" placeholder="検索" />
            <button class="btn btn-dark">複製</button>
            <button class="btn btn-primary" @click="goToCreate()">Add</button> -->
          </div>
        </div>
        <div class="page-card">
          <div class="">
            <table class="table learning-path-table">
              <thead>
                <tr>
                  <th></th>
                  <th>Image</th>
                  <th>Description</th>
                  <!-- <th>Description 2</th>
                  <th>Type 1</th>
                  <th>Type 2</th>
                  <th>Type 3</th> -->
                  <th>公開状況</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ formatdate(top.updated_at) }}</td>
                  <td><img :src="top.file.url" alt="" /></td>
                  <td>
                    <p>
                      {{ top.description_1 }}
                    </p>
                  </td>
                  <!-- <td>
                    <p>
                      {{ top.description_2 }}
                    </p>
                  </td>
                  <td>
                    <p>
                      {{ top.type_1 }}
                    </p>
                  </td>
                  <td>
                    <p>
                      {{ top.type_2 }}
                    </p>
                  </td>

                  <td>
                    <p>
                      {{ top.type_3 }}
                    </p>
                  </td> -->
                  <td>
                    <button
                      class="btn btn-dark"
                      type="button"
                      @click="goToEdit(top.id)"
                    >
                      EDIT
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
  props: { top: Object },
  data() {
    return {
      successToast: false,
    };
  },
  methods: {
    goToCreate() {
      Inertia.get("/admin/top/create");
    },
    goToEdit(id) {
      Inertia.get("/admin/top/" + 1 + "/edit");
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
      setTimeout(() => (this.successToast = false), 3000);
    }
  },
};
</script>
