<template>
  <AppLayoutUser>
    <div class="user-layout">
      <div class="content full-width category-view">
        <div class="page-container">
          <SearchArea :categories="categories" :back_button="true" />
          <section>
            <div class="title-area">
              <h3>{{ courses.name }}</h3>
            </div>
            <div class="card-row" v-if="type == 'single'">
              <div
                class="card video-card"
                v-for="(course, index) in courses.courses"
                :key="index"
              >
                <Link
                  v-if="course.first_video"
                  :href="
                    goToCourseView(
                      course.first_video.id,
                      course.first_video.vedio_id,
                      course.id
                    )
                  "
                >
                  <div class="video-thumb-wrapper">
                    <img :src="course.thumbnail" class="video-thumb" alt="" />
                    <div
                      class="seek-bar"
                      v-if="course.total_watchtime && course.course_activities"
                      :style="'width:' + course.percentage + '%'"
                    ></div>
                  </div>
                </Link>
                <Link
                  v-if="!course.first_video"
                  :href="goToCourseView(null, null, course.id)"
                >
                  <div class="video-thumb-wrapper">
                    <img :src="course.thumbnail" class="video-thumb" alt="" />
                    <div
                      class="seek-bar"
                      v-if="course.total_watchtime && course.course_activities"
                      :style="'width:' + course.percentage + '%'"
                    ></div>
                  </div>
                </Link>
                <div class="card-content">
                  <p class="card-para">
                    {{ course.title }}
                  </p>
                  <div class="info-area">
                    <div class="left">
                      <span
                        ><img :src="'/images/icon-clock.svg'" alt="" />{{
                          formatDurationHrs(course.total_watchtime)
                        }}m
                        <!-- {{formatDurationMinutes(course.total_watchtime)}}m -->
                      </span>
                      <span
                        ><img :src="'/images/icon-tag.svg'" alt="" />{{
                          courses.name
                        }}</span
                      >
                    </div>
                    <div class="right">
                      <Link :href="goToCourse(course.id)">
                        <img :src="'/images/icon-info.svg'" alt=""
                      /></Link>
                      <img
                        :src="'/images/icon-bookmark.svg'"
                        alt=""
                        @click="addToFav(course.id)"
                        v-if="!fav_ids.includes(course.id)"
                      />
                      <img
                        :src="'/images/icon-bookmark-fill.svg'"
                        alt=""
                        @click="addToFav(course.id)"
                        v-if="fav_ids.includes(course.id)"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-row" v-if="type == 'all'">
              <div
                class="card video-card"
                v-for="(course, index) in courses"
                :key="index"
              >
                <Link
                  v-if="course.first_video"
                  :href="
                    goToCourseView(
                      course.first_video.id,
                      course.first_video.vedio_id,
                      course.id
                    )
                  "
                >
                  <div class="video-thumb-wrapper">
                    <img :src="course.thumbnail" class="video-thumb" alt="" />
                    <div
                      class="seek-bar"
                      v-if="course.total_watchtime && course.course_activities"
                      :style="'width:' + course.percentage + '%'"
                    ></div>
                  </div>
                </Link>
                <Link
                  v-if="!course.first_video"
                  :href="goToCourseView(null, null, course.id)"
                >
                  <div class="video-thumb-wrapper">
                    <img :src="course.thumbnail" class="video-thumb" alt="" />
                    <div
                      class="seek-bar"
                      v-if="course.total_watchtime && course.course_activities"
                      :style="'width:' + course.percentage + '%'"
                    ></div>
                  </div>
                </Link>
                <div class="card-content">
                  <p class="card-para">
                    {{ course.title }}
                  </p>
                  <div class="info-area">
                    <div class="left">
                      <span
                        ><img :src="'/images/icon-clock.svg'" alt="" />{{
                          formatDurationHrs(course.total_watchtime)
                        }}m
                        <!-- {{formatDurationMinutes(course.total_watchtime)}}m -->
                      </span>
                      <span
                        ><img :src="'/images/icon-tag.svg'" alt="" />{{
                          course.course_has_categories[0].category.name
                        }}</span
                      >
                    </div>
                    <div class="right">
                      <Link :href="goToCourse(course.id)">
                        <img :src="'/images/icon-info.svg'" alt=""
                      /></Link>
                      <img
                        :src="'/images/icon-bookmark.svg'"
                        alt=""
                        @click="addToFav(course.id)"
                        v-if="!fav_ids.includes(course.id)"
                      />
                      <img
                        :src="'/images/icon-bookmark-fill.svg'"
                        alt=""
                        @click="addToFav(course.id)"
                        v-if="fav_ids.includes(course.id)"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!-- section | ends -->
        </div>
      </div>
    </div>
  </AppLayoutUser>
</template>

<script>
import AppLayoutUser from "@/Layouts/AppLayoutUser.vue";
import Sidebar from "../../../Layouts/User/Sidebar.vue";
import SearchArea from "./Components/SearchArea.vue";
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";

export default {
  components: { AppLayoutUser, Sidebar, SearchArea, Link },
  props: {
    categories: Object,
    courses: Object,
    type: String,
    favs: String,
  },
  // Modal
  data() {
    return {
      open: false,
      fav_ids: [],
      form: {
        email: null,
        password: null,
      },
    };
  },
  created() {
    if (this.favs.length > 0) {
      this.favs.forEach((element) => {
        this.fav_ids.push(element);
      });
    }
  },
  methods: {
    func(id) {
      var SchoolId = 0;
      window.location.href = `/course/details/${id}`;
    },
    goToCourse(id) {
      return "/course/details/" + id;
    },
    goToCourseView(tId, vId, id) {
      console.log(id);
      if (tId != null) {
        return "/course/videoPlay/" + tId + "/" + vId + "/" + id;
      } else {
        return "/course/videosView/" + id;
      }
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
      console.log(target);
      console.log(achived);
      const percent = (achived / target) * 100;
      console.log(percent);
      return percent;
    },
    addToFav(course_id) {
      console.log(course_id);
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
  },
};
</script>
