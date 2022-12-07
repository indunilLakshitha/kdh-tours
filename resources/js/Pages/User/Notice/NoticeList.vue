<template>
    <AppLayoutUser>
        <div class="user-layout">
            <div class="content full-width">
                <div class="page-container user-notice-list">
                    <div class="title-area">
                        <h3>お知らせ一覧</h3>
                    </div>
                    <div class="tag-area">
                        <a href="#" class="btn btn-link">all</a>
                        <a href="#" class="btn btn-link">#NEWS</a>
                        <a href="#" class="btn btn-link">#RELEASE</a>
                        <a href="#" class="btn btn-link">#SYSTEM</a>
                    </div>
                    <div class="page-card">
                        <div class="notice-section">
                            <!--  -->
                            <div class="notice-single">
                                <div class="notice-content">
                                    <div class="info-row">
                                        <div class="dates">2022/00/00</div>
                                        <div class="status release"><!-- 4 status classes - news | release | system -->
                                            RELEASE
                                        </div>
                                    </div>
                                    <a href="#" class="news-para">
                                        タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル
                                        タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル
                                    </a>
                                </div>
                            </div>
                            <!--  -->
                            <div class="notice-single">
                                <div class="notice-content">
                                    <div class="info-row">
                                        <div class="dates">2022/00/00</div>
                                        <div class="status system"><!-- 4 status classes - news | release | system -->
                                            SYSTEM
                                        </div>
                                    </div>
                                    <a href="#" class="news-para">
                                        タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル
                                        タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル
                                    </a>
                                </div>
                            </div>
                            <!--  -->
                            <div class="notice-single" v-for="items in 11">
                                <div class="notice-content">
                                    <div class="info-row">
                                        <div class="dates">2022/00/00</div>
                                        <div class="status news"><!-- 4 status classes - news | release | system -->
                                            NEWS
                                        </div>
                                    </div>
                                    <a href="#" class="news-para">
                                        タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル
                                        タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル
                                    </a>
                                </div>
                            </div>
                            <!--  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayoutUser>
  </template>
  
  <script>
  import AppLayoutUser from "@/Layouts/AppLayoutUser.vue";
  import { Link } from "@inertiajs/inertia-vue3";
  
  export default {
    components: { AppLayoutUser, Link },
    data() {
      return {
        // 
      };
    },
  };
  </script>
  