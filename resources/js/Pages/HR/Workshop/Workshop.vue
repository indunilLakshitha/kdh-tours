<template>
  <AppLayoutUser>
    <div class="layout body-bg">
      <Sidebar />
      <div class="content hr-workshop-edit">
        <div class="page-title-area">
          <h1>ワークショップ管理</h1>
        </div>
        <div
          class="card video-thumb-card"
          v-for="(workshop, index) in workshops"
          :key="index"
        >
          <div class="card-content" @click="goToEdit(workshop.id)">
            <img :src="workshop.thumbnail" class="video-tuhmb" alt="" />
            <div class="video-info">
              <div class="title">
                {{ workshop.name }}
              </div>
              <div class="details-row">
                <div class="details-single">
                  <img :src="'/images/icon-calendar-black.svg'" alt="" />
                  <span v-if="workshop.opening_date == workshop.closing_date">{{getDates(workshop.opening_date,3)}}(木) &nbsp; {{workshop.opening_time}}〜{{workshop.closing_time}}</span>
                  <span v-if="workshop.opening_date != workshop.closing_date">{{getDates(workshop.opening_date,3)}}(木) - {{getDates(workshop.closing_date,3)}}(木) &nbsp; {{workshop.opening_time}}〜{{workshop.closing_time}}</span>
                </div>
                <div class="details-single">
                  <img :src="'/images/icon-tag.svg'" alt="" />
                  <span> {{workshop.categories[0].category.name}}</span>
                </div>
                <div class="details-single">
                  <img :src="'/images/icon-location.svg'" alt="" />
                  <span>{{workshop.venue_d}}</span>
                </div>
                <div class="details-single">
                  <img :src="'/images/icon-contact.svg'" alt="" />
                  <span>{{workshop.capacity}}名</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayoutUser>
</template>

<script>
import AppLayoutUser from "@/Layouts/AppLayoutUser.vue";
import { Inertia } from "@inertiajs/inertia";
import moment from "moment";
import Sidebar from "@/Layouts/User/Sidebar.vue";

export default {
  components: { AppLayoutUser, moment, Sidebar },
  props: {
    workshops: Object,
  },
  data() {
    return {};
  },
  methods: {
    goToCreate() {
      Inertia.get("/workshop/hr/create");
    },
    goToEdit(id) {
      Inertia.get("/workshop/hr/" + id );
    },
    formatdate(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      return formattedDate; //20171019
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

      if (type == 3) {
        return year + "年" + month+ "月"+day +"日";
      }
    },
  },
};
</script>
