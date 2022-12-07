<template>
  <AppLayoutUser>
    <div class="user-layout">
      <div class="content full-width video-view-1">
        <div class="video-area">
          <div class="page-container">
            <h1 class="video-title">{{ this.title }}</h1>
            <div class="video-info-area">
              <img :src="this.courseThumbnail" alt="" class="video" />
              <div class="info-area">
                <ul>
                  <li>
                    <img :src="'/images/icon-clock.svg'" alt="" />{{
                      this.courseDurationMinutes
                    }}
                    m
                    <img
                      :src="'/images/icon-bookmark.svg'"
                      alt=""
                      class="icon-bookmark"
                      @click="addToFav(this.courseId)"
                      v-if="!fav_id"
                    />

                    <img
                      :src="'/images/icon-bookmark-fill.svg'"
                      alt=""
                      class="icon-bookmark"
                      @click="addToFav(this.courseId)"
                      v-if="fav_id"
                    />
                  </li>
                  <li>
                    <img :src="'/images/icon-tag.svg'" alt="" />{{
                      this.courseCategory
                    }}
                  </li>
                  <li v-if="this.courseReport">
                    <img
                      :src="'/images/icon-pencil.svg'"
                      alt=""
                    />テスト・レポート　あり
                  </li>
                </ul>
                <div class="seekbar-area-wrapper">
                  <label for="">進捗状況</label>
                  <div class="seekbar-area">
                    <div class="seekbar-wrapper">
                      <div id="seekbarId" class="seekbar"></div>
                    </div>
                    <label for=""
                      >{{ this.totalPercentage }} <small>%</small></label
                    >
                  </div>
                </div>
                <button
                  v-if="edit_status"
                  type="button"
                  class="btn btn-primary"
                  @click="func(this.courseId)"
                >
                  視聴する
                  <img :src="'/images/icon-arrow-right.svg'" alt="" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="page-container">
          <section>
            <h2>概要</h2>
            <p class="description-para">
              {{ this.courseSummery }}
            </p>
            <h2>詳細</h2>

            <div class="card-white">
              <div
                v-for="(row, index) in this.courseTitlesAndVideo"
                :key="index"
              >
                <p class="para" v-if="row.content != ''">
                  {{ row.content }}
                </p>
                <ul>
                  <div
                    v-for="(row2, index2) in row.belong_videos"
                    :key="index2"
                  >
                    <li>
                      chapter0{{ index2 + 1 }}　{{ row2.belong_video.title
                      }}<span
                        >(
                        {{
                          timeFunction(row2.belong_video.duration_seconds)
                        }})</span
                      >
                    </li>
                  </div>
                </ul>
              </div>
            </div>
          </section>
          <!-- <section class="section-video">
                        <div class="title-area">
                            <h3>おすすめの関連講座</h3>
                            <a href="" class="view-more">もっと見る</a>
                        </div>
                        <div class="card-row">
                            <div class="card video-card" v-for="item in 4">
                                <div class="video-thumb-wrapper">
                                    <img
                                        :src="'/images/home/video.png'"
                                        class="video-thumb"
                                        alt=""
                                    />
                                    <div
                                        class="seek-bar"
                                        :style="'width:58%'"
                                    ></div>
                                </div>
                                <div class="card-content">
                                    <p class="card-para">
                                        タイトルタイトルタイトルタイトルタイトルタイトルタイトルタタイトルタイトルタイトルタイトルタイトルタイトルタイトルタ
                                    </p>
                                    <div class="info-area">
                                        <div class="left">
                                            <span
                                                ><img
                                                    :src="'/images/icon-clock.svg'"
                                                    alt=""
                                                />0h 10m</span
                                            >
                                            <span
                                                ><img
                                                    :src="'/images/icon-tag.svg'"
                                                    alt=""
                                                />カテゴリー</span
                                            >
                                        </div>
                                        <div class="right">
                                            <img
                                                :src="'/images/icon-info.svg'"
                                                alt=""
                                            />
                                            <img
                                                :src="'/images/icon-bookmark.svg'"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section> -->
        </div>
      </div>
    </div>
  </AppLayoutUser>
</template>

<script>
import AppLayoutUser from "@/Layouts/AppLayoutUser.vue";
import { Inertia } from "@inertiajs/inertia";
import Sidebar from "../../../Layouts/User/Sidebar.vue";
import axios from "axios";

export default {
  components: { AppLayoutUser, Sidebar },
  props: {
    courseDetails: Object,
    is_fav: String,
    edit: Boolean,
    firstVideoTitleDetails: Object,
    percentage: 0,
  },
  // Modal
  data() {
    return {
      open: false,
      title: this.courseDetails.title,
      courseDuration: null,
      courseDurationCount: null,
      courseDurationHours: null,
      courseDurationMinutes: null,
      courseCategory: "",
      courseReport: false,
      courseThumbnail: "",
      courseSummery: "",
      courseTitlesAndVideo: Object,
      courseId: null,
      perTaskPercentage: 0,
      totalPercentage: 0,
      checkCompletedVideo: 0,
      checkCompletedReport: 0,
      fav_id: false,
      edit_status: false,
      postViewings: Object,
    };
  },
  created() {
    if (this.is_fav == 1) {
      this.fav_id = true;
    } else {
      this.fav_id = false;
    }

    this.edit_status = this.edit;
    this.totalPercentage = this.percentage;
    // this.perTaskPercentage = (
    //   100 /
    //   (this.courseDetails.total_vedio_count + this.courseDetails.has_reports)
    // ).toFixed(1);
    // this.courseDetails.auth_user_course.course_activities.forEach((activity) => {
    //   if (activity["status"] == 0) {
    //     this.checkCompletedVideo = this.checkCompletedVideo + 1;
    //     this.totalPercentage = (
    //       parseFloat(this.totalPercentage) + parseFloat(this.perTaskPercentage)
    //     ).toFixed(1);
    //   } else {
    //     this.totalPercentage = (
    //       parseFloat(this.totalPercentage) +
    //       (parseFloat(this.perTaskPercentage) /
    //         parseFloat(activity.belong_video["duration_seconds"])) *
    //         parseFloat(activity["total_watch_time"])
    //     ).toFixed(1);
    //   }
    // });
    // if (this.courseDetails.auth_user_course.report_activities) {
    //   if (this.courseDetails.auth_user_course.report_activities.status == 1) {
    //     this.checkCompletedReport = this.checkCompletedReport + 1;
    //     this.totalPercentage =
    //       parseFloat(this.totalPercentage) + parseFloat(this.perTaskPercentage);
    //   }
    // }
    // if (
    //   this.checkCompletedVideo + this.checkCompletedReport ==
    //   this.courseDetails.total_vedio_count + this.courseDetails.total_report_count
    // ) {
    //   this.totalPercentage = 100;
    // }

    this.courseDetails.course_titles.forEach((element1) => {
      element1.belong_videos.forEach((element2) => {
        this.courseDuration += element2["belong_video"].duration_seconds;
      });
    });
    this.courseDurationHours = Math.floor(this.courseDuration / 3600);
    this.courseDurationMinutes = Math.floor(this.courseDuration / 60);
    this.courseCategory =
      this.courseDetails.course_has_categories[0]["category"].name;
    if (this.courseDetails.has_reports == 1) {
      this.courseReport = true;
    }
    this.courseThumbnail = this.courseDetails.thumbnail;
    this.courseSummery = this.courseDetails.summery;
    this.courseTitlesAndVideo = this.courseDetails.course_titles;
    this.courseId = this.courseDetails.id;
    this.postViewings = this.courseDetails.post_viewings;
  },
  mounted() {
    document.getElementById(
      "seekbarId"
    ).style = `width:${this.totalPercentage}%`;
  },
  methods: {
    func(id) {
      var SchoolId = 0;
      //window.location.href = `/course/videosView/${id}`;
      Inertia.get(
        `/course/videoPlay/${this.firstVideoTitleDetails.id}/${this.firstVideoTitleDetails.vedio_id}/${this.courseDetails.id}`
      );
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
            this.fav_id = false;
          } else {
            this.fav_id = true;
          }
        });
    },
    timeFunction(seconds) {
      console.log(seconds);
      var hours = Math.floor(seconds / 3600);
      var min = Math.floor((seconds % 3600) / 60);
      var sec = Math.floor((seconds % 3600) % 60);

      if (hours < 10) {
        hours = `0${hours}`;
      } else {
        hours = `${hours}`;
      }

      if (min < 10) {
        min = `:0${min}`;
      } else {
        min = `:${min}`;
      }

      if (sec < 10) {
        sec = `:0${sec}`;
      } else {
        sec = `:${sec}`;
      }

      return `${hours}${min}${sec}`;
    },
  },
};
</script>
