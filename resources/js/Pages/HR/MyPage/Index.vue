<template>
  <AppLayoutUser>
    <div class="user-layout">
      <Sidebar :username="user.name" />
      <div class="content mypage">
        <a href="" class="btn btn-link">まとめてダウンロード</a>
        <div class="page-container">
          <div class="two-column">
            <section class="achievement-section">
              <div class="title-area">
                <div class="avatar">
                  <img v-if="user.avatar" :src="user.avatar" class="" alt="" />
                  <img
                    v-if="!user.avatar"
                    :src="'https://www.business2community.com/wp-content/uploads/2017/08/blank-profile-picture-973460_640.png'"
                    class=""
                    alt=""
                  />
                </div>
                <h3>{{ user.name }} さんの受講状況</h3>
                <!-- <a href="" class="view-more">マイアカウントへ</a> -->
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
                        <span class="value">{{ user.achived_videos }}</span>
                      </div>
                      <div class="row-single">
                        <span class="info">達成レポート数</span>
                        <span class="value">{{ user.achived_reports }}</span>
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
                      background="#fff"
                    />
                  </div>
                </div>
              </div>
            </section>
            <!--  -->
            <section class="attendance-section" v-if="user.user_type == 1">
              <div class="title-area">
                <h3>受講状況一覧</h3>
                <!-- <a href="" class="view-more">学習プランの確認</a> -->
              </div>
              <div class="card scroll">
                <div class="card-content">
                  <table>
                    <thead>
                      <tr>
                        <td>&nbsp;</td>
                        <td>視聴件数</td>
                        <td>レポート達成数</td>
                        <td>目標視聴数</td>
                        <td>レポート目標数</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="active">
                        <td>達成目標</td>
                        <td></td>
                        <td></td>
                        <td>{{ target_v }}</td>
                        <td>{{ target_r }}</td>
                      </tr>
                      <tr
                        class="active"
                        v-for="other_user in other_users"
                        :key="other_user.id"
                      >
                        <td>{{ other_user.name }}</td>
                        <td>{{ other_user.achived_videos }}</td>
                        <td>{{ other_user.achived_reports }}</td>
                        <td>{{ other_user.target_vedio_count }}</td>
                        <td>{{ other_user.target_report_count }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </section>
            <!--  -->
            <section class="lecture-history-section">
              <div class="title-area">
                <h3>受講履歴</h3>
                <Link href="/course/history" class="view-more">受講履歴</Link>
              </div>
              <div class="card">
                <div class="card-content">
                  <div
                    class="expand-area"
                    v-for="(product, index) in product_keys"
                    :key="index"
                  >
                    <div class="row-single">
                      <span class="info">{{
                        product.user.name ? product.user.name : "未登録"
                      }}</span>
                      <span class="mid-value">{{ product.product_key }}</span>
                      <span class="value" @click="showAndHide(index)">
                        <img
                          :src="'/images/icon-arrow-bottom.svg'"
                          :class="{
                            up: !showing.includes(index),
                            down: showing.includes(index),
                          }"
                          alt=""
                        />
                      </span>
                    </div>
                    <div class="expand-content" v-if="showing.includes(index)">
                      <div
                        class="single"
                        v-for="(activity, no) in product.user.user_courses_limited"
                        :key="no"
                      >
                        <div class="status" v-if="activity.progress_status != 2">
                          未達
                        </div>
                        <div class="status success" v-if="activity.progress_status == 2">
                          達成
                        </div>
                        <div class="info-column">
                          <div class="date" v-if="activity.progress_status != 2">
                            {{ formatdateWithDevide(activity.created_at) }} -
                          </div>
                          <div class="date" v-if="activity.progress_status == 2">
                            {{ formatdateWithDevide(activity.created_at) }} -
                            {{ formatdateWithDevide(activity.updated_at) }}
                          </div>
                          <p>{{ activity.course.title }}</p>
                        </div>
                        <!-- <button class="btn btn-secondary" v-if="activity.progress_status == 0" @click="goToCourse(activity.course.id)">
                          <img :src="'/images/icon-pen.svg'" alt="" />
                          レポート編集
                        </button> -->
                        <button
                          class="btn btn-secondary"
                          v-if="activity.progress_status != 2"
                          @click="goToCourse(activity.course.id, product.user.id)"
                        >
                          <!-- <img :src="'/images/icon-pen.svg'" alt="" /> -->
                          プレビュー
                        </button>
                        <button
                          class="btn btn-primary"
                          v-if="activity.progress_status == 2"
                          @click="goToCourse(activity.course.id, product.user.id)"
                        >
                          プレビュー
                        </button>
                      </div>
                      <!-- <div class="single" v-for="items in 3">
                        <div class="status success">達成</div>
                        <div class="info-column">
                          <div class="date">2022年00月00日 - 2022年00月00日</div>
                          <p>タイトルタイトルタイトルタイトルタイトルタイトル</p>
                        </div>
                        <button class="btn btn-primary">プレビュー</button>
                      </div> -->
                    </div>
                  </div>
                </div>
              </div>
            </section>
            <!--  -->
          </div>
          <!-- two-column -->
          <div class="two-column">
            <section class="user-settings-section">
              <div class="title-area">
                <h3>ユーザー設定</h3>
                <Link href="/profile" class="view-more">修正・変更する</Link>
              </div>
              <div class="card">
                <div class="card-content">
                  <div class="row-area">
                    <div class="row-single">
                      <span class="info">名前(ふりがな）</span>
                      <span class="value"
                        >{{ user.name }} ({{ user.name_in_furi }})
                      </span>
                    </div>
                    <div class="row-single">
                      <span class="info">会社名</span>
                      <span class="value">{{ user.company_name }}</span>
                    </div>
                    <div class="row-single">
                      <span class="info">役職</span>
                      <span class="value">{{ user.position }}</span>
                    </div>
                    <div class="row-single">
                      <span class="info">メールアドレス</span>
                      <span class="value">{{ user.email }}</span>
                    </div>
                    <div class="row-single">
                      <span class="info">ニックネーム</span>
                      <span class="value">{{ user.nick_name }}</span>
                    </div>
                    <div class="row-single">
                      <span class="info">パスワード</span>
                      <span class="value">***************</span>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            <!--  -->
            <section class="contract-section" v-if="user.user_type == 1">
              <div class="title-area">
                <h3>契約情報</h3>
                <Link href="/license" class="view-more">ライセンスキーを追加する</Link>
              </div>
              <div class="card">
                <div class="card-content">
                  <div class="row-area">
                    <div class="row-single">
                      <span class="info">契約期間</span>
                      <!-- <span class="value">2022年11月1日〜2023年4月1日</span> -->
                      <span class="value"
                        >{{ formatdateWithDevide(contract_start) }}〜{{
                          formatdateWithDevide(contract_end)
                        }}</span
                      >
                    </div>
                    <!-- <div class="row-single">
                      <span class="info">支払い方法</span>
                      <span class="value"
                        >請求書払い<img :src="'/images/icon-warn.svg'" alt=""
                      /></span>
                    </div>
                    <div class="row-single gray-bg">
                      <div>
                        <p>
                          申し訳ありませんが、お支払いが確認できておりません。<br />
                          お手数をおかけいたしますが、ご登録メールアドレスに案内を送らせて
                          いただいておりますので、ご確認をよろしくお願いいたします。<br />
                          なおステータス反映まで数日時間がかかりますので、行き違いになって
                          おりましたらご了承ください。
                        </p>
                        <div class="date">2022年00月00日</div>
                      </div>
                    </div> -->
                    <div class="row-single">
                      <span class="info">ライセンスキー一覧</span>
                      <span class="value" @click="showProductKeyArea"
                        ><img :src="'/images/icon-arrow-bottom.svg'" class="down" alt=""
                      /></span>
                    </div>
                    <div v-if="product_key_show == 1">
                      <div
                        class="row-single"
                        v-for="product_key in product_keys"
                        :key="product_key.id"
                      >
                        <span class="info">{{ product_key.user.name }}</span>
                        <span class="mid-value">{{ product_key.product_key }}</span>
                        <span class="date-value">
                          <div class="wrapper">
                            <span>発行日</span>
                            <span>{{
                              formatdateWithDevide(product_key.created_at)
                            }}</span>
                          </div>
                          <div class="wrapper">
                            <span>更新日</span>
                            <span>{{
                              formatdateWithDevide(product_key.expired_at)
                            }}</span>
                          </div>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            <!--  -->
          </div>
          <!-- two-column -->

          <!-- Modal Start -->
          <div v-if="open" class="modal mypage_video_modal">
            <div class="modal-content">
              <button @click="open = false" class="modal-close">
                <img :src="'/images/icon-modal-close.svg'" alt="" />
              </button>
              <p class="para">山田太郎 さんに次見てほしい</p>
              <div class="card-title">関連講座</div>
              <div class="card-row">
                <div class="card video-card" v-for="item in 3">
                  <div class="video-thumb-wrapper">
                    <img :src="'/images/home/video.png'" class="video-thumb" alt="" />
                    <div class="seek-bar" :style="'width:58%'"></div>
                  </div>
                  <div class="card-content">
                    <p class="card-para">
                      タイトルタイトルタイトルタイトルタイトルタイトルタイトルタタイトルタイトルタイトルタイトルタイトルタイトルタイトルタ
                    </p>
                    <div class="info-area">
                      <div class="left">
                        <span><img :src="'/images/icon-clock.svg'" alt="" />0h 10m</span>
                        <span
                          ><img :src="'/images/icon-tag.svg'" alt="" />カテゴリー</span
                        >
                      </div>
                      <div class="right">
                        <img :src="'/images/icon-info.svg'" alt="" />
                        <img :src="'/images/icon-bookmark.svg'" alt="" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- card-row -->
            </div>
          </div>
          <!-- Modal End -->
        </div>
      </div>
      <!-- Toast -->
      <div
        class="toast toast-success"
        v-if="alertVisible == 'visible' && message != null"
      >
        <div class="toast-body" v-text="message"></div>
        <img
          :src="'/images/icon-toast-close.svg'"
          class="btn-close"
          @click="closeAlert"
        />
      </div>
      <!--  -->
    </div>
  </AppLayoutUser>
</template>

<script>
import AppLayoutUser from "@/Layouts/AppLayoutUser.vue";
import { Inertia } from "@inertiajs/inertia";
import Sidebar from "../../../Layouts/User/Sidebar.vue";
import moment from "moment";
import { Link } from "@inertiajs/inertia-vue3";
// Vue circle progress import
import "vue3-circle-progress/dist/circle-progress.css";
import CircleProgress from "vue3-circle-progress";

export default {
  components: { AppLayoutUser, CircleProgress, Sidebar, moment, Link },
  props: {
    user: Object,
    other_users: Object,
    product_keys: Object,
    total_vedio_count: String,
    total_watch_time: String,
    total_report_count: String,
    achieved_report_count: String,
    target_r: String,
    target_v: String,
    contract_start: String,
    contract_end: String,
    total_views: String,
    message: String,
    alertStatus: String,
  },
  data() {
    return {
      // Modal
      open: false,
      alertVisible: "!visible",
      product_key_show: 0,
      percent: 0,
      showing: [],
    };
  },
  methods: {
    goToProfileEdit() {
      Inertia.get("/profile");
    },
    goToCourse(course_id, user_id) {
      Inertia.get("/course/details/" + course_id + "/" + user_id);
      // Inertia.get("/course/details/"+course_id);
    },
    formatdate(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      return formattedDate; //20171019
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
    showProductKeyArea() {
      if (this.product_key_show == 1) {
        this.product_key_show = 0;
      } else {
        this.product_key_show = 1;
      }
    },
    closeAlert() {
      this.alertVisible = "!visible";
    },
    showAndHide(id) {
      if (this.showing.includes(id)) {
        var index = this.showing.indexOf(id);
        if (index !== -1) {
          this.showing.splice(index, 1);
        }
      } else {
        this.showing.push(id);
      }
    },
  },
  created() {
    if (this.total_vedio_count > 0 && this.total_report_count > 0) {
      this.percent =
        ((this.user.achived_videos + this.user.achived_reports) /
          (this.total_vedio_count + this.total_report_count)) *
        100;
    } else if (this.total_vedio_count > 0 && !this.total_report_count > 0) {
      this.percent =
        ((this.user.achived_videos + this.user.achived_reports) /
          this.total_vedio_count) *
        100;
    } else if (!this.total_vedio_count > 0 && this.total_report_count > 0) {
      this.percent =
        ((this.user.achived_videos + this.user.achived_reports) /
          this.total_report_count) *
        100;
    }

    if (this.percent > 100) {
      this.percent = 100;
    }
    this.alertVisible = this.alertStatus;
    setInterval(() => {
      this.closeAlert();
    }, 4000);
  },
};

// Collapse expand
</script>
