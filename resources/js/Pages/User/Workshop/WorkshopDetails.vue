<template>
  <AppLayoutUser>
    <div class="user-layout">
      <div class="content full-width workshop-details">
        <div class="video-area">
          <div class="page-container">
            <h1 class="video-title">
              {{ workshop.name.slice(0, 40) }}<br />{{ workshop.name.slice(40) }} 
            </h1>
            <div class="video-info-area">
              <img :src="workshop.thumbnail" alt="" class="video" />
              <div class="info-area">
                <ul>
                  <li>
                    <label>
                      <img :src="'/images/icon-calendar-black.svg'" alt="" />日時
                    </label>
                    <span class="info" v-if="workshop.opening_date == workshop.closing_date"> {{getDates(workshop.opening_date,3)}}(木) &nbsp; {{workshop.opening_time}}〜{{workshop.closing_time}}  </span>
                    <span class="info" v-if="workshop.opening_date != workshop.closing_date"> {{getDates(workshop.opening_date,3)}}(木) - {{getDates(workshop.closing_date,3)}}(木) &nbsp; {{workshop.opening_time}}〜{{workshop.closing_time}} </span>
                  </li>
                  <li>
                    <label>
                      <img :src="'/images/icon-tag.svg'" alt="" />カテゴリー
                    </label>
                    <span class="info"> {{workshop.categories[0].category.name}} </span>
                  </li>
                  <li>
                    <label> <img :src="'/images/icon-location.svg'" alt="" />場所 </label>
                    <span class="info"> {{workshop.venue_d}} </span>
                  </li>
                  <li>
                    <label>
                      <img :src="'/images/icon-contact.svg'" alt="" />定員数
                    </label>
                    <span class="info"> {{workshop.capacity}}名 </span>
                  </li>
                </ul>
                <div class="btn-row">
                  <button type="button" class="btn btn-primary" v-if="workshop.applied ==0 || workshop.applied == 2 && workshop.expired ==0" @click="goToApply(workshop.id,1)">
                    参加申込 <img :src="'/images/icon-arrow-right.svg'" alt="" />
                  </button>
                  <button type="button" class="btn btn-primary" v-if="workshop.applied == 2 && workshop.expired == 0" @click="goToApply(workshop.id,2)">参加希望</button>
                  <button type="button" class="btn btn-secondary" v-if="workshop.expired ==1" >受付終了</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="page-container">
          <section>
            <h2>概要</h2>
            <p class="description-para">
              {{workshop.summery}}
            </p>
            <h2>説明</h2>
            <div class="card-white">
              <p class="para">
                {{workshop.detail_description}}
              </p>
            </div>
            <div class="border-box">
              <h2>講師略歴</h2>
              <p class="description-para">
                {{workshop.instructor_profile}}
              </p>
            </div>
          </section>
          <p class="center-para">上記の内容で申込予約をしますか？</p>
          <div class="btn-area">
            <button type="button" class="btn btn-primary" @click="goToApply(workshop.id,1)">
              参加申込 <img :src="'/images/icon-arrow-right.svg'" alt="" />
            </button>
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

export default {
  props: {
    workshop: Object,
  },
  components: { AppLayoutUser, Sidebar },
  data() {
    return {
      thumbnail: "",
    };
  },
  created() {
    // console.log(this.workshop);
  },
  methods: {
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
    goToApply(id,type){
      console.log('applied');
      return Inertia.get("/workshop/"+id+"/apply/"+type);
    },
    goToHopeToApply(id){
      console.log('hoped');
      return Inertia.get("/workshop/"+id+"/apply/"+0);
    }
  },
};
</script>
