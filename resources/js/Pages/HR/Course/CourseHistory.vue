<template>
  <AppLayoutUser>
    <div class="user-layout">
      <Sidebar />
      <div class="content course-history">
        <div class="page-title-area">
          <h1>受講履歴</h1>
        </div>
        <div class="page-card">
          <div
            class="collapse-area"
            v-for="(product, index) in product_keys"
            :key="index"
          >
            <div class="collapse-title">
              <span class="username">{{ product.user.name ? product.user.name : '未登録' }}</span> <span>{{ product.product_key }}</span>
              <img
                :src="'/images/icon-arrow-bottom.svg'"
                alt="" :class="{'up': !showing.includes(index), 'down': showing.includes(index)}"
                @click="showAndHide(index)"
              />
           
            </div>
            <div class="collapse-content" v-if="showing.includes(index)">
              <!-- Unachieved -->
              <div
                class="row"
                v-for="(activity, no) in product.user.user_courses_limited"
                :key="no"
              >
                <div class="status unachieved" v-if="activity.progress_status != 2">
                  未達
                </div>
                <div class="status achieved" v-if="activity.progress_status == 2">
                  達成
                </div>
                <div class="info-area">
                  <div class="date-range" v-if="activity.progress_status != 2">
                    {{ formatdateWithDevide(activity.created_at) }} -
                  </div>
                  <div class="date-range" v-if="activity.progress_status == 2">
                    {{ formatdateWithDevide(activity.created_at) }} -
                    {{ formatdateWithDevide(activity.updated_at) }}
                  </div>
                  <p>{{ activity.course.title }}                    
                  </p>
                  <div class="figures" v-if="activity.progress_status != 2">
                    <img :src="'/images/icon-clock.svg'" alt="" />
                    <div  class="time-range">{{(activity.course.userwatchtime/60).toFixed(2)}}<span>/{{ (activity.course.total_watchtime/60).toFixed(2) }} m</span></div>
                  </div>
                  <div class="figures" v-if="activity.progress_status == 2">
                    <img :src="'/images/icon-clock.svg'" alt="" />
                    <div  class="time-range"><span>{{ (activity.course.total_watchtime/60).toFixed(2) }} m</span></div>
                  </div>
                </div>
                <button class="btn btn-secondary" v-if="activity.progress_status != 2" @click="goToCourse(activity.course.id)">
                  <!-- <img :src="'/images/icon-pen.svg'" alt="" /> -->
                  プレビュー
                </button>
                <button class="btn btn-primary" v-if="activity.progress_status == 2" @click="goToCourse(activity.course.id)">
                  <!-- <img :src="'/images/icon-pen.svg'" alt="" /> -->
                  プレビュー
                </button>
              </div>
              <!-- Achieved -->
              <!-- <div class="row" v-for="items in 15">
                <div class="status achieved">達成</div>
                <div class="info-area">
                  <div class="date-range">2022年00月00日 - 2022年00月00日</div>
                  <p>
                    タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル
                    タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル
                  </p>
                  <div class="figures">
                    <img :src="'/images/icon-clock.svg'" alt="" />
                    <div class="time-range">60:00<span>/80:00</span></div>
                  </div>
                </div>
                <button class="btn btn-primary">プレビュー</button>
              </div> -->
              <!--  -->
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
import Sidebar from "../../../Layouts/User/Sidebar.vue";
import { reactive } from "vue";
import moment from "moment";

export default {
  props: { product_keys: Object },
  components: { AppLayoutUser, Sidebar },
  data() {
    return {
      showing: [],
      toggle: 'up',
    };
  },
  methods: {
    showAndHide(id) {
      if (this.showing.includes(id)) {
        this.toggle = 'down';
        var index = this.showing.indexOf(id);
        if (index !== -1) {
          this.showing.splice(index, 1);
        }
      } else {
        this.showing.push(id);
      }
    },
    formatdateWithDevide(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      var year =
        formattedDate.charAt(0) +
        formattedDate.charAt(1) +
        formattedDate.charAt(2) +
        formattedDate.charAt(3);
      var month = formattedDate.charAt(5) + formattedDate.charAt(6);
      var d = formattedDate.charAt(8) + formattedDate.charAt(9);
      return year + "年" + month + "月" + d + "日";
    },
    goToCourse(course_id) {
      Inertia.get("/course/details/"+course_id);
    },
  },
};
</script>
