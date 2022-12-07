<template>
  <ul class="sidebar-watch">
    <div class="title-container">
      <p class="title two-line">{{ this.courseDetails.title }}</p>
      <div class="seekbar-area">
        <div class="seekbar-wrapper">
          <div id="seekbarId" class="seekbar" style="width: 0%"></div>
        </div>
        <label for="">{{ this.totalPercentage }} <small>% 完了</small></label>
      </div>
    </div>
    <div v-for="(row, index) in this.courseTitlesAndVideo" :key="index">
      <div class="major-title" v-if="row.content != ''">
        {{ row.content }}
      </div>
      <div v-for="(row2, index2) in row.belong_videos" :key="index2">
        <li>
          <Link
            :id="'linkWatched' + row2.id"
            class=""
            @click="playVideo(row2.belong_video.id, this.courseDetails.id, row2.id)"
          >
            <img :src="'/images/icon-movie.svg'" alt="" />
            <div>
              <div class="chapter one-line">Section 0{{ index2 + 1 }}</div>
              <div class="title">
                {{ row2.belong_video.title }}
              </div>
              <div class="duration">
                {{ timeFunction(row2.belong_video.duration_seconds) }}
              </div>
            </div>
          </Link>
        </li>
      </div>
    </div>
    <li v-if="this.courseDetails.has_reports != 0">
      <Link
        class="align-items-center"
        :id="'documentLink'"
        @click="report(this.courseDetails.id)"
      >
        <img :src="'/images/icon-align-left.svg'" alt="" />
        <div class="chapter">レポート</div>
      </Link>
    </li>
    <div v-if="this.activeDocWork == false">
      <li class="border-white bg-gray">
        <Link style="pointer-events: none; display: inline-block" :id="'document'">
          <div class="mb-1">
            <div class="chapter mb-3">資料ダウンロード</div>
            <div class="duration">動画・テストを全て終了するとダウンロードできます。</div>
          </div>
        </Link>
      </li>
      <li class="bg-gray">
        <Link style="pointer-events: none; display: inline-block" :id="'worksheet'">
          <div class="mb-1">
            <div class="chapter mb-3">ワークシートダウンロード</div>
            <div class="duration">動画・テストを全て終了するとダウンロードできます。</div>
          </div>
        </Link>
      </li>
    </div>
    <div v-if="this.activeDocWork == true">
      <li class="border-white bg-gray active">
        <Link @click="documentDownload(this.courseDetails.id)" :id="'document'">
          <div class="mb-1">
            <div class="chapter mb-3">資料ダウンロード</div>
            <div class="duration">動画・テストを全て終了するとダウンロードできます。</div>
          </div>
        </Link>
      </li>
      <li class="bg-gray active">
        <Link @click="worksheetDownload(this.courseDetails.id)" :id="'worksheet'">
          <div class="mb-1">
            <div class="chapter mb-3">ワークシートダウンロード</div>
            <div class="duration">動画・テストを全て終了するとダウンロードできます。</div>
          </div>
        </Link>
      </li>
    </div>
  </ul>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

export default {
  //name: "ChideComponent",

  components: {
    Link,
  },
  inject: ["courseDetails"],
  data() {
    return {
      msg: "Child Method Message",
      perTaskPercentage: 0,
      totalPercentage: 0,
      call: true,
      activeDocWork: false,
      checkCompletedVideo: 0,
      checkCompletedReport: 0,
      courseActivitiesVideosIds: [],
      courseVideosIds: [],
      sideBarHide: false,
      percentage: 0,
    };
  },
  created() {
    this.perTaskPercentage = (
      100 /
      (this.courseDetails.total_vedio_count + this.courseDetails.has_reports)
    ).toFixed(1);

    this.courseDetails.auth_user_course.course_activities.forEach((activity) => {
      if (activity["status"] == 0) {
        this.checkCompletedVideo = this.checkCompletedVideo + 1;
        this.totalPercentage = (
          parseFloat(this.totalPercentage) + parseFloat(this.perTaskPercentage)
        ).toFixed(1);
      } else {
        this.totalPercentage = (
          parseFloat(this.totalPercentage) +
          (parseFloat(this.perTaskPercentage) /
            parseFloat(activity.belong_video["duration_seconds"])) *
            parseFloat(activity["total_watch_time"])
        ).toFixed(1);
      }
    });
    if (this.courseDetails.auth_user_course.report_activities) {
      if (this.courseDetails.auth_user_course.report_activities.status == 1) {
        this.checkCompletedReport = this.checkCompletedReport + 1;
        this.totalPercentage =
          parseFloat(this.totalPercentage) + parseFloat(this.perTaskPercentage);
      }
    }
    if (
      this.checkCompletedVideo + this.checkCompletedReport ==
      this.courseDetails.total_vedio_count + this.courseDetails.has_reports
    ) {
      this.totalPercentage = 100;
    }

    if (this.totalPercentage > 100) {
      this.totalPercentage = 100;
    }

    this.courseTitlesAndVideo = this.courseDetails.course_titles;
    if (this.courseDetails.auth_user_course.progress_status == 2) {
      this.activeDocWork = true;
    }
    this.courseDetails.auth_user_course.course_activities.forEach((element) => {
      if (element.status == 0) {
        this.courseActivitiesVideosIds.push(element.video_id);
      }
    });
    this.courseTitlesAndVideo.forEach((element) => {
      element.belong_videos.forEach((element2) => {
        this.courseVideosIds.push(element2.vedio_id);
      });
    });
  },
  props: {
    callMethod: "",
  },
  setup() {},
  mounted() {
    document.getElementById("seekbarId").style = `width:${this.totalPercentage}%`;
    this.courseActivitiesVideosIds.forEach((element) => {
      if (this.courseVideosIds.includes(element)) {
        this.courseTitlesAndVideo.forEach((element1) => {
          element1.belong_videos.forEach((element2) => {
            if (element2.belong_video.id == element) {
              document
                .getElementById(`linkWatched${element2.id}`)
                .classList.add("watched");
            }
          });
        });
      }
    });
    if (this.courseDetails.has_reports != 0) {
      if (this.courseDetails.auth_user_course.report_activities) {
        if (this.courseDetails.auth_user_course.report_activities.status == 1) {
          document.getElementById(`documentLink`).classList.add("watched");
        }
      }
    }
    if (window.location.pathname.split("/").length == 6) {
      var videoId = window.location.pathname.split("/")[3];
      document.getElementById(`linkWatched${videoId}`).classList.add("active");
    }

    if (window.location.pathname.split("/")[2] == "report") {
      document.getElementById(`documentLink`).classList.add("active");
    }
    // if (window.location.pathname.split("/")[2] == "document") {
    //     document
    //         .getElementById(`document`)
    //         .classList.add("active");
    // }
    // if (window.location.pathname.split("/")[2] == "worksheet") {
    //     document
    //         .getElementById(`worksheet`)
    //         .classList.add("active");
    // }
  },
  methods: {
    funcHideNav() {
      var SchoolId = 0;
      window.location.href = `/course/detail`;
    },
    playVideo(videoId, courseId, id) {
      Inertia.get(`/course/videoPlay/${id}/${videoId}/${courseId}`);
      document.getElementById(`linkWatched${id}`).classList.add("active");
    },
    report(courseId) {
      Inertia.get(`/course/report/${courseId}`);
    },
    documentDownload(courseId) {
      Inertia.get(`/course/document/${courseId}`);
    },
    worksheetDownload(courseId) {
      Inertia.get(`/course/worksheet/${courseId}`);
    },
    timeFunction(seconds) {
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
