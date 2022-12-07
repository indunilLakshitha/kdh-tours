<template>
  <AppLayoutUser>
    <div class="user-layout">
      <div class="content full-width workshop-complete">
        <div class="hero-text-area">
          <h1 class="hero-text">
            予約申し込みが完了しました！<br/>メールアドレスに連絡が届きますのでご確認ください。
          </h1>
        </div>
        <div class="video-area">
          <div class="page-container">
            <div class="video-info-area">
              <img :src="workshop.thumbnail" alt="" class="video" />
              <div class="info-area">
                <h3 class="video-title">
                  {{ workshop.name.slice(0, 40) }}<br />{{ workshop.name.slice(40) }}
                </h3>
                <ul>
                  <li>
                    <label>
                      <img :src="'/images/icon-calendar-black.svg'" alt="" />日時
                    </label>
                    <span
                      class="info"
                      v-if="workshop.opening_date == workshop.closing_date"
                    >
                      {{ getDates(workshop.opening_date, 3) }}(木) &nbsp; {{workshop.opening_time}}〜{{workshop.closing_time}} 
                    </span>
                    <span
                      class="info"
                      v-if="workshop.opening_date != workshop.closing_date"
                    >
                      {{ getDates(workshop.opening_date, 3) }}(木) -
                      {{ getDates(workshop.closing_date, 3) }}(木) &nbsp;
                      {{ workshop.opening_time }}〜{{ workshop.closing_time }}
                    </span>
                  </li>
                  <li>
                    <label>
                      <img :src="'/images/icon-tag.svg'" alt="" />カテゴリー
                    </label>
                    <span class="info"> {{ workshop.categories[0].category.name }} </span>
                  </li>
                  <li>
                    <label> <img :src="'/images/icon-location.svg'" alt="" />場所 </label>
                    <span class="info"> {{ workshop.venue_d }} </span>
                  </li>
                  <li>
                    <label>
                      <img :src="'/images/icon-contact.svg'" alt="" />定員数
                    </label>
                    <span class="info"> {{ workshop.capacity }}名 </span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="page-container">
          <h2>本ワークショップには事前課題があります</h2>
          <section class="info-box">
            <div class="info-box-header">
              ワークショップ開催日までに、以下の事前学習を完了させてください。
            </div>
            <div class="info-box-body">
              <div class="info-box-title">事前学習</div>
              <section class="section-video">
                <div class="card-row">
                  <div
                    class="card video-card"
                    v-for="(course, index) in workshop.courses"
                    :key="index"
                  >
                    <Course :course="course.course" />
                  </div>
                </div>
              </section>
              <div class="info-box-title">事前課題ダウンロード</div>
              <section class="form-section">
                <div v-for="(assignment, index) in workshop.assignments" :key="index">
                  <p v-if="assignment.type == 1">
                    {{ assignment.title }}
                  </p>
                  <div class="download-row" v-if="assignment.type == 2">
                    <span class="title">資料のタイトル</span>
                    <img
                      :src="'/images/icon-download.svg'" 
                      @click.prevent="downloadItem(assignment.url)"
                      alt=""
                    />
                  </div>
                </div>
                <div class="info-box-title">事前課題のアップロード</div>
                <div class="form-group">
                  <div class="file-upload-wrapper">
                    <input type="file" class="form-control file-upload file" />
                    <label for="">ファイルをアップロード</label>
                  </div>
                </div>
                <div class="title-closable">
                  アップロード後、資料のタイトルがここに表示されます。
                  <img :src="'/images/icon-close-circle.svg'" alt="" class="btn-close" />
                </div>
              </section>
            </div>
          </section>
          <p class="center-para">
            ※お申し込みのワークショップ詳細ページで再度ご確認いただけます
          </p>
          <div class="btn-area">
            <button type="button" class="btn btn-primary">トップページへ戻る</button>
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
import Course from "../../components/User/Course.vue";

export default {
  props: {
    workshop: Object,
  },
  components: { AppLayoutUser, Sidebar, Course },
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
        return year + "年" + month + "月" + day + "日";
      }
    },
    goToApply(id) {
      console.log("sadsad");
      return Inertia.get("/workshop/" + id + "/apply/" + 1);
    },
    goToHopeToApply(id) {
      console.log("sadsad");
      return Inertia.get("/workshop/" + id + "/apply/" + 0);
    },
    downloadItem(url) {
      window.open(
        url,
        "_blank" // <- This is what makes it open in a new window.
      );
    },
  },
};
</script>
