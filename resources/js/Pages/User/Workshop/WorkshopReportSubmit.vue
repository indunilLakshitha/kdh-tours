<template>
    <AppLayoutUser>
        <div class="user-layout">
            <div class="content full-width workshop-submit">
                <div class="video-area">
                    <div class="page-container">
                        <h1 class="video-title">
                            タイトルタイトルタイトルタイトル<br/>タイトルタイトルタイトルタイトルタイトルタイトル
                        </h1>
                        <div class="video-info-area">
                            <img
                                :src="'/images/workshop/workshop1.png'"
                                alt=""
                                class="video"
                            />
                            <div class="info-area">
                                <ul>
                                    <li>
                                        <label>
                                            <img :src="'/images/icon-calendar-black.svg'" alt="" />日時
                                        </label>
                                        <span class="info">
                                            2022年00月00日(木) &nbsp; 00:00〜00:00
                                        </span>
                                    </li>
                                    <li>
                                        <label>
                                            <img :src="'/images/icon-tag.svg'" alt="" />カテゴリー
                                        </label>
                                        <span class="info">
                                            カテゴリー名
                                        </span>
                                    </li>
                                    <li>
                                        <label>
                                            <img :src="'/images/icon-location.svg'" alt="" />場所
                                        </label>
                                        <span class="info">
                                            オンライン(zoom)
                                        </span>
                                    </li>
                                    <li>
                                        <label>
                                            <img :src="'/images/icon-contact.svg'" alt="" />定員数
                                        </label>
                                        <span class="info">
                                            30名
                                        </span>
                                    </li>
                                </ul>
                                <div class="btn-row">
                                    <button type="button" class="btn btn-primary">
                                        参加申込済
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-container" style="width: 100% !important;">
                    <section class="info-box">
                        <div class="info-box-header">ワークショップ開催日までに、以下の事前学習を完了させてください。</div>
                        <div class="info-box-body">
                            <!-- <div class="info-box-title">事前学習</div>
                            <section class="section-video">
                                <div class="card-row">
                                    <div class="card video-card" v-for="item in 3">
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
                            <div class="page-container">
                                <div class="info-box-title">事前課題ダウンロード</div>
                                <section class="form-section">
                                    <p>
                                        説明文テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト
                                    </p>
                                    <div class="download-row">
                                        <span class="title">資料のタイトル</span>
                                        <img :src="'/images/icon-download.svg'" alt="" />
                                    </div>
                                    <div class="info-box-title">事前課題のアップロード</div>
                                    <div class="form-group">
                                        <div class="file-upload-wrapper">
                                            <input type="file" class="form-control file-upload file">
                                            <label for="">サムネイルをアップロード</label>
                                        </div>
                                    </div>
                                    <div class="title-closable">
                                        アップロード後、資料のタイトルがここに表示されます。
                                        <img :src="'/images/icon-close-circle.svg'" alt="" class="btn-close" />
                                    </div>
                                </section>
                            </div>
                        </div>
                    </section>
                    <!-- <section class="details-section">
                        <div class="container">
                            <h2>概要</h2>
                            <p class="description-para">
                                テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト
                            </p>
                            <h2>説明</h2>
                            <div class="card-white">
                                <p class="para">
                                    テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト
                                </p>
                            </div>
                            <div class="border-box">
                                <h2>講師略歴</h2>
                                <p class="description-para">
                                    テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト
                                </p>
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

export default {
    components: { AppLayoutUser, Sidebar },
    data() {
        return {
            //
        };
    },
};
</script>
