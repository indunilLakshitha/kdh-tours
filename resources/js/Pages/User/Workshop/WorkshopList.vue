<template>
  <AppLayoutUser>
    <div class="user-layout">
      <div class="content full-width user-workshop-list">
        <div class="page-container">
          <section class="section-video">
            <div class="title-area">
              <h3>ワークショップ・イベント一覧</h3>
            </div>
            <div class="card-row">
              <!-- <div class="card video-card">
                <div class="video-thumb-wrapper applied">
                  <img :src="'/images/workshop/workshop1.png'" class="thumb" alt="" />
                </div>
                <div class="card-content">
                  <div class="info-area">
                    <div class="left-col">
                      <small>2022/11</small>
                      <span>29</span>
                    </div>
                    <div class="right-col">
                      <p>
                        タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card video-card">
                <div class="video-thumb-wrapper date-passed">
                  <img :src="'/images/workshop/workshop1.png'" class="thumb" alt="" />
                </div>
                <div class="card-content">
                  <div class="info-area">
                    <div class="left-col">
                      <small>2022/11</small>
                      <span>29</span>
                    </div>
                    <div class="right-col">
                      <p>
                        タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル
                      </p>
                    </div>
                  </div>
                </div>
              </div> -->

              <div
                class="card video-card"
                v-for="(workshop, index) in workshops"
                :key="index"
              >
                <!-- <Link :href="goToView(workshop.id)"> -->
                  <div
                    :class="{
                      'video-thumb-wrapper': true,
                      applied: workshop.applied,
                      'date-passed': workshop.expired,
                      'expired-or-full': workshop.expired,
                      expected: workshop.expected,
                    }"
                    @click="goToView(workshop.id)"
                  >
                    <img :src="workshop.thumbnail" class="thumb" alt="" />
                  </div>
                  <div class="card-content">
                    <div class="info-area">
                      <div class="left-col">
                        <small>{{ getDates(workshop.opening_date, 1) }}</small>
                        <span>{{ getDates(workshop.opening_date, 2) }}</span>
                      </div>
                      <div class="right-col">
                        <p>
                          {{ workshop.name }}
                        </p>
                      </div>
                    </div>
                  </div>
                <!-- </Link> -->
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </AppLayoutUser>
</template>

<script>
import AppLayoutUser from "@/Layouts/AppLayoutUser.vue";
import { Inertia } from "@inertiajs/inertia";
import Sidebar from "../../../Layouts/User/Sidebar.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default {
  props: { workshops: Object },
  components: { AppLayoutUser, Sidebar, Link },
  // Modal
  data() {
    return {
      open: false,
    };
  },
  created() {},
  methods: {
    goToView(id) {
      // return "/workshop/" + id;
      return Inertia.get("/workshop/"+id);
    },
    getDates(date, type) {
      var dt = new Date(date);
      var month = dt.getMonth() + 1;
      var day = dt.getDate();
      var year = dt.getFullYear();

      if (type == 1) {
        return year + "/" + month;
      }

      if (type == 2) {
        return day;
      }
    },
  },
};
</script>
