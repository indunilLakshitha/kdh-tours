<template>
  <div v-for="(course, index) in courses" :key="index" class="card video-card">
    <Link
      v-if="course.first_video"
      :href="
        goToCourseView(
          course.first_video.id,
          course.first_video.vedio_id,
          course.first_video.course_id
        )
      "
    >
      <div class="video-thumb-wrapper">
        <img :src="course.thumbnail" class="video-thumb" alt="" />
        <div class="seek-bar" :style="'width:' + course.watchedPercentage + '%'"></div>
      </div>
    </Link>
    <Link v-if="!course.first_video" :href="goToCourseView(null, null, course.id)">
      <div class="video-thumb-wrapper">
        <img :src="course.thumbnail" class="video-thumb" alt="" />
        <div class="seek-bar" :style="'width:' + course.watchedPercentage + '%'"></div>
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
              Math.round(course.total_watchtime / 60)
            }}m</span
          >
          <span
            ><img :src="'/images/icon-tag.svg'" alt="" />{{
              course.course_has_categories[0].category.name
            }}</span
          >
        </div>
        <div class="right">
          <Link :href="goToCourse(course.id)"
            ><img :src="'/images/icon-info.svg'" alt=""
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
</template>
<script>
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";

export default {
  props: { courses: Object },
  components: {
    Link,
  },
  data() {
    return {
      fav_ids: [],
    };
  },
  created() {
    this.getMyFav();
  },
  methods: {
    // addToFav(course_id) {
    //   let user_token = document
    //     .querySelector('meta[name="csrf-token"]')
    //     .getAttribute("content");

    //   axios
    //     .post("/favourites", {
    //       course_id: course_id,
    //       headers: {
    //         _token: user_token,
    //       },
    //     })
    //     .then((response) => {
    //       if (response.data.type == "REMOVED") {
    //         var new_fav = [];
    //         this.fav_ids.filter((element) => {
    //           if (element != response.data.course_id) {
    //             new_fav.push(element);
    //           }
    //           this.fav_ids = new_fav;
    //         });
    //       } else {
    //         this.fav_ids.push(response.data.course_id);
    //       }
    //     });
    // },
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
    goToCourse(id) {
      return "/course/details/" + id;
    },
    // goToCourseView(id) {
    //   return "/course/videosView/" + id;
    // },
    goToCourseView(tId, vId, id) {
      if (tId != null) {
        return "/course/videoPlay/" + tId + "/" + vId + "/" + id;
      } else {
        return "/course/videosView/" + id;
      }
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
  },
};
</script>
