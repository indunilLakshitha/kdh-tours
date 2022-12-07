<template>
  <AppLayoutUser>
    <div class="user-layout">
      <div class="content full-width home">
        <div class="hero-text-area">
          <h1 class="hero-text">
            ようこそ {{ user.name }} さん！ <br />
            さっそく動画視聴してみましょう。
          </h1>
        </div>
        <div class="page-container">
          <section>
            <div class="title-area">
              <h3>講座一覧</h3>
              <Link href="/category" class="view-more">もっと見る</Link>
            </div>
            <div class="card-row">
              <div class="card video-card" v-for="course in courses" :key="course.id">
                <Link
                  v-if="course.course.first_video"
                  :href="
                    goToCourseView(
                      course.course.first_video.id,
                      course.course.first_video.vedio_id,
                      course.course.first_video.course_id
                    )
                  "
                >
                  <div class="video-thumb-wrapper">
                    <img :src="course.course.thumbnail" class="video-thumb" alt="" />
                    <div
                      class="seek-bar"
                      :style="'width:' + course.course.percentage + '%'"
                    ></div>
                  </div>
                </Link>
                <Link
                  v-if="!course.course.first_video"
                  :href="goToCourseView(null, null, course.id)"
                >
                  <div class="video-thumb-wrapper">
                    <img :src="course.course.thumbnail" class="video-thumb" alt="" />
                    <div
                      class="seek-bar"
                      :style="'width:' + course.course.percentage + '%'"
                    ></div>
                  </div>
                </Link>
                <div class="card-content">
                  <p class="card-para">
                    {{ course.course.title }}
                  </p>
                  <div class="info-area">
                    <div class="left">
                      <span
                        ><img :src="'/images/icon-clock.svg'" alt="" />{{
                          formatDurationHrs(course.course.total_watchtime)
                        }}m
                        <!-- {{ formatDurationMinutes(course.course.total_watchtime) }}m -->
                      </span>
                      <span
                        ><img :src="'/images/icon-tag.svg'" alt="" />{{
                          course.course.course_has_categories[0].category.name
                        }}</span
                      >
                    </div>
                    <div class="right">
                      <Link :href="goToCourse(course.course.id)"
                        ><img :src="'/images/icon-info.svg'" alt=""
                      /></Link>
                      <img
                        :src="'/images/icon-bookmark.svg'"
                        alt=""
                        @click="addToFav(course.course.id)"
                        v-if="!fav_ids.includes(course.course.id)"
                      />
                      <img
                        :src="'/images/icon-bookmark-fill.svg'"
                        alt=""
                        @click="addToFav(course.course.id)"
                        v-if="fav_ids.includes(course.course.id)"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <section class="watching-section">
            <div class="title-area">
              <h3>視聴中の講座</h3>
              <Link href="/category" class="view-more">もっと見る</Link>
            </div>
            <div class="card-row">
              <div
                class="card video-card"
                v-for="course in courses_watching"
                :key="course.id"
              >
                <Link
                  v-if="course.course.first_video"
                  :href="
                    goToCourseView(
                      course.course.first_video.id,
                      course.course.first_video.vedio_id,
                      course.course.first_video.course_id
                    )
                  "
                >
                  <div class="video-thumb-wrapper">
                    <img :src="course.course.thumbnail" class="video-thumb" alt="" />
                    <div
                      v-if="
                        course.course.total_watchtime && course.course.course_activities
                      "
                    >
                      <div
                        class="seek-bar"
                        :style="'width:' + course.percentage + '%'"
                      ></div>
                    </div>
                  </div>
                </Link>
                <Link
                  v-if="!course.course.first_video"
                  :href="goToCourseView(null, null, course.id)"
                >
                  <div class="video-thumb-wrapper">
                    <img :src="course.course.thumbnail" class="video-thumb" alt="" />
                    <div
                      v-if="
                        course.course.total_watchtime && course.course.course_activities
                      "
                    >
                      <div
                        class="seek-bar"
                        :style="'width:' + course.percentage + '%'"
                      ></div>
                    </div>
                  </div>
                </Link>
                <div class="card-content">
                  <p class="card-para">
                    {{ course.course.title }}
                  </p>
                  <div class="info-area">
                    <div class="left">
                      <span
                        ><img :src="'/images/icon-clock.svg'" alt="" />{{
                          formatDurationHrs(course.course.total_watchtime)
                        }}m
                        <!-- {{ formatDurationMinutes(course.course.total_watchtime) }}m -->
                      </span>
                      <span
                        ><img :src="'/images/icon-tag.svg'" alt="" />{{
                          course.course.course_has_categories[0].category.name
                        }}</span
                      >
                    </div>
                    <div class="right">
                      <Link :href="goToCourse(course.course.id)">
                        <img :src="'/images/icon-info.svg'" alt=""
                      /></Link>
                      <img
                        :src="'/images/icon-bookmark.svg'"
                        alt=""
                        @click="addToFav(course.course.id)"
                        v-if="!fav_ids.includes(course.course.id)"
                      />
                      <img
                        :src="'/images/icon-bookmark-fill.svg'"
                        alt=""
                        @click="addToFav(course.course.id)"
                        v-if="fav_ids.includes(course.course.id)"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <div class="section-row">
            <div class="notice-section">
              <div class="title-area">
                <h3>お知らせ</h3>
                <a href="" class="view-more">もっと見る</a>
              </div>
              <div class="card">
                <div class="card-content">
                  <div class="row-area">
                    <div
                      class="row-single"
                      v-for="(notice, index) in notices"
                      :key="index"
                    >
                      <a :href="notice.link" target="_blank">
                        <div class="row-single">
                          <div class="left">
                            <div class="dates">{{ formatdate(notice.created_at) }}</div>
                            <div class="status news">
                              {{ notice.categories[0].category.category }}
                            </div>
                          </div>
                          <div class="right">
                            <p>
                              {{ notice.title }}
                            </p>
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- notice-section | end -->
            <div class="achievement-section">
              <div class="title-area">
                <h3>受講状況</h3>
                <Link href="/my-page" class="view-more">マイアカウントへ</Link>
              </div>
              <div class="card">
                <div class="card-content">
                  <div class="left">
                    <div class="row-area">
                      <div class="row-single">
                        <span class="info">総視聴時間</span>
                        <span class="value">{{ total_watch_time }}<small>h</small></span>
                      </div>
                      <div class="row-single">
                        <span class="info">動画視聴数</span>
                        <span class="value">{{ total_views }}</span>
                      </div>
                      <div class="row-single">
                        <span class="info">達成レポート数</span>
                        <span class="value">{{ achieved_report_count }}</span>
                      </div>
                      <div class="row-single">
                        <span class="info">目標動画視聴数</span>
                        <span class="value">{{ total_vedio_count }}</span>
                      </div>
                      <div class="row-single">
                        <span class="info">目標レポート数</span>
                        <span class="value">{{ total_report_count }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="right">
                    <circle-progress
                      :show-percent="true"
                      :percent="percent"
                      fill-color="#18B2A6"
                    />
                  </div>
                </div>
              </div>
            </div>
            <!-- achievement-section | end -->
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
import { Link } from "@inertiajs/inertia-vue3";
import moment from "moment";
// Vue circle progress import
import "vue3-circle-progress/dist/circle-progress.css";
import CircleProgress from "vue3-circle-progress";
import axios from "axios";

export default {
  components: { AppLayoutUser, CircleProgress, Sidebar, Link },
  props: {
    course_categories: Object,
    notices: Object,
    courses: Object,
    courses_watching: Object,
    total_vedio_count: String,
    total_watch_time: String,
    total_views: String,
    total_report_count: String,
    achieved_report_count: String,
    user: Object,
    favs: Object,
  },
  // Modal
  data() {
    return {
      open: false,
      percent: 0,
      fav_ids: [],
    };
  },
  created() {
    this.getMyFav();
    // if (this.favs.length > 0) {
    //   this.favs.forEach((element) => {
    //     this.fav_ids.push(element);
    //   });
    // }
    if (this.total_vedio_count > 0 && this.total_report_count > 0) {
      this.percent =
        ((this.total_views + this.achieved_report_count) /
          (this.total_vedio_count + this.total_report_count)) *
        100;
    } else if (this.total_vedio_count > 0 && !this.total_report_count > 0) {
      this.percent =
        ((this.total_views + this.achieved_report_count) / this.total_vedio_count) * 100;
    } else if (!this.total_vedio_count > 0 && this.total_report_count > 0) {
      this.percent =
        ((this.total_views + this.achieved_report_count) / this.total_report_count) * 100;
    }

    if (this.percent > 100) {
      this.percent = 100;
    }
  },
  methods: {
    goToCreate() {
      Inertia.get("/admin/course/create");
    },
    goToCourse(id) {
      return "/course/details/" + id;
    },
    goToCourseView(tId, vId, id) {
      if (tId != null) {
        return "/course/videoPlay/" + tId + "/" + vId + "/" + id;
      } else {
        return "/course/videosView/" + id;
      }
    },
    formatdate(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      return formattedDate; //20171019
    },
    formatDurationHrs(duration) {
      const durationMins = duration / 60;
      return Math.round(durationMins);
      // const durationHrs = duration / (60 * 60);
      // return Math.round(durationHrs);
    },
    formatDurationMinutes(duration) {
      const durationHrs = duration % (60 * 60);
      return Math.round(durationHrs / 60);
    },
    calPercent(target, achived) {
      var percent = 0;
      if (target > 0) {
        percent = (achived / target) * 100;
      } else {
        percent = 0;
      }

      return percent;
    },
    addToFav(course_id) {
      let user_token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

      axios
        .post("/favourites", {
          course_id: course_id,
          headers: {
            _token: user_token,
          },
        })
        .then((response) => {
          if (response.data.type == "REMOVED") {
            var new_fav = [];
            this.fav_ids.filter((element) => {
              if (element != response.data.course_id) {
                new_fav.push(element);
              }
              this.fav_ids = new_fav;
            });
          } else {
            this.fav_ids.push(response.data.course_id);
          }
        });
    },
    checkFavList(course_id) {
      let check = false;
      this.fav_ids.forEach((id) => {
        if (id == course_id) {
          if (check == false) {
            check = true;
          }
        }
      });

      return check;
    },
    getMyFav() {
      let user_token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

      axios
        .get("/favourites/my", {
          headers: {
            _token: user_token,
          },
        })
        .then((response) => {
          response.data.favs.forEach((element) => {
            this.fav_ids.push(element);
          });
        });
    },
  },
};
</script>
