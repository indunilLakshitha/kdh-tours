<template>
  <AppLayoutUser>
    <div class="user-layout">
      <div class="content full-width workshop-apply">
        <div class="hero-text-area" v-if="this.just_applied">
          <h1 class="hero-text">
            予約申し込みが完了しました！<br />メールアドレスに連絡が届きますのでご確認ください。
          </h1>
        </div>
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
                    <span
                      class="info"
                      v-if="workshop.opening_date == workshop.closing_date"
                    >
                      {{ getDates(workshop.opening_date, 3) }}(木) &nbsp;
                      {{ workshop.opening_time }}〜{{ workshop.closing_time }}
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
                  <li v-if="workshop.venue ==2">
                    <label>  </label>
                    <span class="info"> {{ workshop.place_url }} </span>
                  </li>
                  <li>
                    <label>
                      <img :src="'/images/icon-contact.svg'" alt="" />定員数
                    </label>
                    <span class="info"> {{ workshop.capacity }}名 </span>
                  </li>
                </ul>
                <div class="btn-row">
                  <button type="button" class="btn btn-primary">参加申込済</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="page-container" style="width: 100% !important">
          <section class="info-box">
            <div class="info-box-header">
              ワークショップ開催日までに、以下の事前学習を完了させてください。
            </div>
            <div class="page-container">
                <div class="info-box-body">
              <div class="info-box-title">事前学習</div>
              <section class="section-video">
                <div class="card-row">
                  <!-- <div
                    class="card video-card"
                    v-for="(course, index) in workshop.courses"
                    :key="index"
                  > -->
                  <Course :courses="workshop.courses" />
                  <!-- </div> -->
                </div>
              </section>
              <section
                class="form-section mb-40"
                v-for="(assignment, index) in assignments"
                :key="index"
              >
                <div class="info-box-title">事前課題ダウンロード - {{ index + 1 }}</div>
                <div>
                  <p>
                    {{ assignment.assignment.description }}
                  </p>
                  <div class="download-row">
                    <span class="title">{{ assignment.assignment.title }}</span>
                    <img
                      :src="'/images/icon-download.svg'"
                      @click.prevent="downloadItem(assignment.assignment.url)"
                      alt=""
                    />
                  </div>
                </div>
                <div class="info-box-title">事前課題のアップロード - {{ index + 1 }}</div>
                <div class="form-group">
                  <div class="file-upload-wrapper">
                    <input
                      type="file"
                      class="form-control file-upload file"
                      @change="uploadAssignment($event, assignment.id, index)"
                    />
                    <label for="">ファイルをアップロード</label>
                  </div>
                </div>
                <div class="title-closable" v-if="!assignment.sumbmit_url">
                  アップロード後、資料のタイトルがここに表示されます。
                  <img :src="'/images/icon-close-circle.svg'" alt="" class="btn-close" />
                </div>
                <div
                  class="title-closable"
                  v-if="assignment.sumbmit_url"
                  @click="removeAssignment(assignment.id, index)"
                >
                  {{ trimFileName(assignment.sumbmit_url.split("temp/")[1], 1) }}
                  <img :src="'/images/icon-close-circle.svg'" alt="" class="btn-close" />
                    </div>
                </section>
                </div>
            </div>
          </section>
          <section class="details-section" v-if="!this.just_applied">
            <div class="container">
              <h2>概要</h2>
              <p class="description-para">
                {{ workshop.summery }}
              </p>
              <h2>説明</h2>
              <div class="card-white">
                <p class="para">
                  {{ workshop.detail_description }}
                </p>
              </div>
              <div class="border-box">
                <h2>講師略歴</h2>
                <p class="description-para">
                  {{ workshop.instructor_profile }}
                </p>
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
import Course from "../../components/User/Course.vue";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
  props: { workshop: Object, just_applied: Boolean },
  components: { AppLayoutUser, Sidebar, Course },
  data() {
    return {
      assignments: [],
    };
  },
  created() {
    this.assignments = this.workshop.workshop_activity;
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
    downloadItem(url) {
      window.open(
        url,
        "_blank" // <- This is what makes it open in a new window.
      );
    },
    uploadAssignment(event, id, index) {
      let user_token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
      let formData = new FormData();

      formData.append("document", event.target.files[0]);
      formData.append("id", id);
      axios
        .post("/workshop/assignments", formData, {
          headers: {
            _token: user_token,
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.data.success == true) {
            this.assignments[index].sumbmit_url = response.data.url.url;
          } else {
            console.log("failed");
          }
        });
    },
    removeAssignment(assignment_id, index) {
      let user_token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

      axios
        .delete("/workshop/assignments/" + assignment_id, {
          headers: {
            _token: user_token,
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.data.success == true) {
            this.assignments[index].sumbmit_url = null;
          } else {
            console.log("failed");
          }
        });
    },
    trimFileName(filename, type) {
      var file_name_to_show;

      if (type == 1) {
        // files with ext
        var name = filename.substr(0, filename.lastIndexOf("."));
        var ext = filename.substr(filename.lastIndexOf(".") + 1);

        if (name.length > 15) {
          file_name_to_show = name.slice(0, 15) + "...." + ext;
        } else {
          file_name_to_show = name + "." + ext;
        }
        return file_name_to_show;
      } else {
        var name = filename;
        if (name.length > 15) {
          file_name_to_show = name.slice(0, 15) + "....";
        } else {
          file_name_to_show = name;
        }
        return file_name_to_show;
      }
    },
  },
};
</script>
