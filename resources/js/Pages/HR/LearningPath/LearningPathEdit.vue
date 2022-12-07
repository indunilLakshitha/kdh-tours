<template>
    <AppLayoutUser>
      <div class="layout body-bg">
        <Sidebar />
        <div class="content learning-path-edit">
          <div class="page-title-area">
            <h1>ラーニングパスの追加変更</h1>
            <h5>御社ご契約のラーニングパスの追加変更をしていただけます。変更後はページ下の「更新」を押してください。</h5>
          </div>
          <div class="page-card scrollable">
            <div class="">
              <table class="table learning-path-edit-table">
                <thead>
                  <tr>
                    <th>順番</th>
                    <th>サムネイル</th>
                    <th>カテゴリー</th>
                    <th>動画プロパティ</th>
                    <th>必修</th>
                    <th>削除</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="items in 5">
                    <td>
                        <div class="tooltip" >
                            <img :src="'/images/icon-draggable.svg'" alt="" />
                            <span class="tooltiptext">上下にドラッグしてください</span>
                        </div>
                    </td>
                    <td><img :src="'/images/course-thumb.png'" alt="" /></td>
                    <td>
                        トピック <br/>
                        講座パッケージ名
                    </td>
                    <td>
                      <div class="border-left">
                        <p>
                            タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル
                        </p>
                        <div class="info-area">
                            <div class="info-single">
                                <img :src="'/images/icon-clock.svg'" alt="" />
                                0h 10m
                            </div>
                            <div class="info-single">
                                <img :src="'/images/icon-tag.svg'" alt="" />
                                カテゴリー
                            </div>
                        </div>
                      </div>
                    </td>
                    <td>
                        <label for="default-toggle" class="inline-flex relative items-center cursor-pointer">
                            <input type="checkbox" value="" id="default-toggle" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                            </div>
                        </label>
                    </td>
                    <td><img :src="'/images/icon-close-circle.svg'" alt="" /></td>
                  </tr>
                  <tr>
                    <td colspan="6">
                        <button class="btn btn-link">
                            <img :src="'/images/icon-plus-circle-sml.svg'" alt="">追加する
                        </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="btn-area">
            <button class="btn btn-secondary">キャンセル</button>
            <button class="btn btn-primary">更新</button>
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
  
  export default {
    components: { AppLayoutUser, moment, Sidebar },
    props: {
    },
    data() {
      return {
      };
    },
  };
  </script>
  