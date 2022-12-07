<template>
    <AppLayoutAdmin>
        <div class="layout">
            <Sidebar />
            <div class="content video-list">
                <div class="page-title-area">
                    <h1>動画管理</h1>
                    <div class="search-form">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="タイトル・キーワード・カテゴリー"
                        />
                        <button class="btn btn-primary" @click="goToCreate">
                            新規動画
                        </button>
                    </div>
                </div>
                <div class="page-card">
                    <div class="">
                        <table class="table course-list">
                            <thead>
                                <tr>
                                    <td colspan="6">
                                        <div class="icon-filter">
                                            <img
                                                :src="'/images/icon-filter-list.svg'"
                                                alt=""
                                            />フィルタ
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>日付<span class="icon-sort"></span></th>
                                    <th>サムネイル</th>
                                    <!-- <th>
                                        カテゴリー<span
                                            class="icon-sort"
                                        ></span>
                                    </th> -->
                                    <th>
                                        タイトル<span class="icon-sort"></span>
                                    </th>
                                    <th>ステータス</th>
                                    <th>編集</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(video, index) in videos"
                                    :key="index"
                                >
                                    <td>
                                        {{ formatdate() }}
                                    </td>
                                    <td>
                                        <img :src="video.thumbnail" alt="" />
                                    </td>
                                    <!-- <td>カテゴリー</td> -->
                                    <td>
                                    <div class="border-left">
                                            <p>
                                                {{ video.title }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-link"
                                            v-if="video.status === 1"
                                        >
                                            <img
                                                :src="'/images/icon-eye-primary.svg'"
                                                alt=""
                                            />公開
                                        </button>
                                        <button
                                            class="btn btn-link"
                                            v-if="video.status === 0"
                                        >
                                            <img
                                                :src="'/images/icon-eye.svg'"
                                                alt=""
                                            />非公開
                                        </button>
                                    </td>
                                    <td v-if="video.has_reports === 1">あり</td>
                                    <td v-if="video.has_reports === 0">無し</td>
                                    <td>
                                        <button
                                            class="btn btn-dark"
                                            type="button"
                                            @click="goToEdit(video.id)"
                                        >
                                            編集
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- <div class="btn-area">
                    <button class="btn btn-secondary">非公開で保存</button>
                    <button class="btn btn-primary">公開する</button>
                </div> -->
            </div>
        </div>
        <!-- Toast -->
        <div class="toast toast-success" v-if="successToast" sty>
            <div class="toast-body">動画が保存されました。</div>
            <img
                :src="'/images/icon-toast-close.svg'"
                class="btn-close"
                @click="this.successToast = false"
            />
        </div>
        <!--  -->
    </AppLayoutAdmin>
</template>

<script>
import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin.vue";
import { Inertia } from "@inertiajs/inertia";
import moment from "moment";

export default {
    components: { AppLayoutAdmin, moment },
    props: {
        videos: Object,
        success: Object,
    },
    // Modal
    data() {
        return {
            open: false,
            form: {
                email: null,
                password: null,
            },
            session: [],
            successToast: false,
        };
    },
    mounted() {
        this.loadSession;
    },
    created() {
        let urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get("success")) {
            if (urlParams.get("success") == 1) {
                this.successToast = true;
                setTimeout(() => {
                    this.successToast = false;
                }, 5000);
            }
        }
    },
    methods: {
        goToCreate() {
            Inertia.get("/admin/video/create");
        },
        goToEdit(id) {
            var url = "/admin/video/" + id + "/edit";
            Inertia.get(url);
        },
        formatdate(date) {
            const formattedDate = moment(date).format("YYYY/MM/DD");
            return formattedDate; //20171019
        },
        loadSession: function () {
            axios
                .get("messageCreateVideo")
                .then((response) => {
                    this.session = response.data;
                    this.successToast = true;
                    setTimeout(() => (this.successToast = false), 3000);
                })
                .catch(function (error) {});
        },
    },
};
</script>
