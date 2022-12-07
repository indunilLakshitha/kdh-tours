<template>
    <AppLayoutUser>
      <div class="user-layout body-bg">
        <Sidebar />
        <div class="content mypage">
          <div class="page-title-area">
            <h1>ワークショップ管理</h1>
          </div>
          <div class="page-container">
            <div class="two-column">
                <section class="achievement-section">
                    <div class="card">
                        <div class="card-content">
                            <div class="left">
                                <div class="row-area">
                                <div class="row-single">
                                    <span class="info">総視聴時間</span>
                                    <span class="value">10<small>h</small></span>
                                </div>
                                <div class="row-single">
                                    <span class="info">動画視聴数</span>
                                    <span class="value">10</span>
                                </div>
                                <div class="row-single">
                                    <span class="info">達成レポート数</span>
                                    <span class="value">10</span>
                                </div>
                                <div class="row-single">
                                    <span class="info">目標動画視聴数</span>
                                    <span class="value">10</span>
                                </div>
                                <div class="row-single">
                                    <span class="info">目標レポート数</span>
                                    <span class="value">10</span>
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
            </div>
          </div>
        </div>
      </div>
    </AppLayoutUser>
  </template>
  
  <script>
  import AppLayoutUser from "@/Layouts/AppLayoutUser.vue";
  import { Inertia } from "@inertiajs/inertia";
  import moment from "moment";
  import Sidebar from "@/Layouts/User/Sidebar.vue";
  import "vue3-circle-progress/dist/circle-progress.css";
  import CircleProgress from "vue3-circle-progress";
  
  export default {
    components: { AppLayoutUser, CircleProgress, moment, Sidebar },
    props: {
    },
    data() {
      return {
      };
    },
  };
  </script>
  