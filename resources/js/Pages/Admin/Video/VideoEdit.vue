<template>
    <AppLayoutAdmin>
        <div class="content video-register">
            <div class="page-title-area">
                <h1>動画登録</h1>
            </div>

            <div class="page-card">
                <div class="form">
                    <div class="form-group sml">
                        <label
                            >動画名
                            <div class="char-count">(50文字以内)</div></label
                        >
                        <div class="flex-column">
                            <input
                                required
                                type="text"
                                v-model="form.title"
                                id="title"
                                class="form-control"
                                @keyup="titleText"
                            />

                            <div class="char-count" v-if="countText(1, 1) > 50">
                                <span>({{ countText(1, 1) }}/50文字以内)</span>
                            </div>
                            <div
                                class="char-count"
                                v-if="countText(1, 1) <= 50"
                            >
                                ({{ countText(1, 1) }}/50文字以内)
                            </div>
                            <div class="error" v-if="errors.title">
                                {{ errors.title }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group sml">
                        <label>動画概要</label>
                        <div class="flex-column">
                            <textarea
                                v-model="form.summary"
                                id="summary"
                                required
                                class="form-control"
                                @keyup="summaryText"
                            ></textarea>
                            <div class="error" v-if="errors.summary">
                                {{ errors.summary }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group sml">
                        <label
                            >サムネイル<br />アップロード
                            <div class="char-count">(5MB以内)</div></label
                        >
                        <div class="flex-column">
                            <div class="d-flex">
                                <div class="file-upload-wrapper">
                                    <input
                                        type="file"
                                        class="form-control file-upload"
                                        ref="thumbnail"
                                        id="thumbnail"
                                        @change="thumbUploadStatus($event)"
                                    />
                                    <label for=""
                                        >サムネイルをアップロード</label
                                    >
                                </div>
                                <div class="img-preview-wrapper" v-if="!thumb_upload">
                                    <img
                                        :src="thumb_link"
                                        class="icon-draggable"
                                        alt=""
                                    />
                                    <button
                                        class="btn-close"
                                        type="button"
                                        @click="removeImage()"
                                    >
                                        <img
                                            :src="'/images/icon-close-circle.svg'"
                                            alt=""
                                        />
                                    </button>
                                </div>
                            </div>

                            <div class="error" v-if="errors.thumbnail">
                                {{ errors.thumbnail }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group sml">
                        <label>詳細設定</label>
                        <div class="flex-column">
                            <div class="file-upload-wrapper">
                                <input
                                    type="file"
                                    class="form-control file-upload with-file"
                                    ref="video"
                                    id="video"
                                    @change="videoUploadStatus($event)"
                                />
                                <label for="">{{ this.fileName }}</label>
                            </div>
                            <div class="error" v-if="errors.video">
                                {{ errors.video }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="form-group sml"
                        v-if="!video_upload && !thumb_upload"
                    >
                        <label>&nbsp;</label>
                        <div class="flex-column">
                            <div class="row-bordered">
                                <div class="">
                                    <img
                                        :src="thumb_link"
                                        class="thumb"
                                        alt=""
                                    />
                                </div>
                                <div>
                                    <p
                                        id="titleTemp"
                                        v-if="!video_upload && !thumb_upload"
                                        class="para"
                                    >
                                        {{ form.title }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn-area">
                <button
                    id="tempVideo"
                    class="btn btn-secondary"
                    @click="submitVideoTemp"
                >
                    一時保存
                </button>
                <button
                    id="submitVideo"
                    class="btn btn-primary"
                    @click="submitVideoPublish"
                >
                    登録
                </button>
            </div>
        </div>
        <!-- Toast -->
        <div class="toast toast-danger" v-if="successToast" sty>
            <div class="toast-body">動画登録失敗しました。</div>
            <img
                :src="'/images/icon-toast-close.svg'"
                class="btn-close"
                @click="this.successToast = false"
            />
        </div>
        <!--  -->
        <!-- Spinner / Loader -->
        <div class="spinner-wrapper" v-if="isLoading">
            <div class="lds-dual-ring"></div>
        </div>
        <!--  -->
    </AppLayoutAdmin>
</template>

<script>
import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin.vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import { useForm } from "@inertiajs/inertia-vue3";
export default {
    components: { AppLayoutAdmin },
    props: {
        course_categories: Object,
        errors: Object,
        video: Object,
    },
    // Modal
    data() {
        return {
            open: false,
            form: useForm({
                title: "",
                summary: "",
                video: null,
                thumbnail: null,
                status: "",
                id: null,
            }),
            successToast: false,
            thumb_upload: true,
            thumb_link: null,
            video_upload: true,
            video_link: null,
            fileName: "",
            isLoading: false,
        };
    },
    created() {
        this.form.title = this.video.title;
        this.form.summary = this.video.summary;
        this.form.thumbnail = this.video.thumbnail;
        this.form.video = this.video.videos;
        this.thumb_link = this.video.thumbnail;
        this.video_upload = this.video.videos;
        this.fileName = this.video.title;
        if (this.video.thumbnail) {
            this.thumb_upload = false;
        }
        if (this.video.videos) {
            this.video_upload = false;
        }
    },
    methods: {
        submitVideoPublish() {
            if (this.$refs.thumbnail.files[0]) {
                this.form.thumbnail = this.$refs.thumbnail.files[0];
            }
            if (this.$refs.video.files[0]) {
                this.form.video = this.$refs.video.files[0];
            }
            this.form.status = 1;
            this.form.id = this.video.id;
            this.isLoading = true;
            document.getElementById("submitVideo").disabled = true;
            document.getElementById("tempVideo").disabled = true;
            this.form.post(route("video.update", this.video.id), {
                onSuccess: () => {
                    this.successToast = true;
                    this.isLoading = false;
                    setTimeout(() => (this.successToast = false), 3000);
                },
                onFinish: () => {
                    document.getElementById("submitVideo").disabled = false;
                    document.getElementById("tempVideo").disabled = false;
                    this.is_submit_enabled = false;
                    this.isLoading = false;
                },
            });
        },
        submitVideoTemp() {
            if (this.$refs.thumbnail.files[0]) {
                this.form.thumbnail = this.$refs.thumbnail.files[0];
            }
            if (this.$refs.video.files[0]) {
                this.form.video = this.$refs.video.files[0];
            }
            this.form.status = 0;
            this.form.id = this.video.id;
            this.isLoading = true;
            document.getElementById("submitVideo").disabled = true;
            document.getElementById("tempVideo").disabled = true;
            this.form.post(route("video.update", this.video.id), {
                onSuccess: () => {
                    this.successToast = true;
                    this.isLoading = false;
                    setTimeout(() => (this.successToast = false), 3000);
                },
                onFinish: () => {
                    document.getElementById("submitVideo").disabled = false;
                    document.getElementById("tempVideo").disabled = false;
                    this.is_submit_enabled = false;
                    this.isLoading = false;
                },
            });
        },
        countText(type, modl) {
            let max = 0;
            let now = 0;

            if (type == 1) {
                max = 50; //for title
                now = this.form.title.length;
            } else if (type == 2) {
                max = 50; //for title
            }
            return now;
        },
        thumbUploadStatus(event) {
            this.thumb_upload = false;
            let input = this.$refs.thumbnail;
            let file = input.files;
            var extension = file[0].type;
            let acceptedMimes = "jpg,jpeg,png,gif,svg"
                .trim()
                .split(",")
                .map((extension) => `image/${extension}`);

            if (acceptedMimes.includes(extension)) {
                if (file && file[0]) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.thumb_link = e.target.result;
                    };
                    reader.readAsDataURL(file[0]);
                }
                this.form.thumbnail = this.$refs.thumbnail.files[0];
                this.$emit("input", file[0]);
                this.errors.thumbnail = "";

                document.getElementById("submitVideo").disabled = false;
                document.getElementById("tempVideo").disabled = false;
            } else {
                this.errors.thumbnail = "画像ファイルのみをサポート";
                document.getElementById("submitVideo").disabled = true;
                document.getElementById("tempVideo").disabled = true;
            }

            if (file[0].size < 5242880) {
                if (file && file[0]) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.thumb_link = e.target.result;
                    };
                    reader.readAsDataURL(file[0]);
                }
                this.form.thumbnail = this.$refs.thumbnail.files[0];
                this.$emit("input", file[0]);
                this.errors.thumbnail = "";
                document.getElementById("submitVideo").disabled = false;
                document.getElementById("tempVideo").disabled = false;
            } else {
                this.errors.thumbnail = "5MB未満のファイルのみをサポート";
                document.getElementById("submitVideo").disabled = true;
                document.getElementById("tempVideo").disabled = true;
            }
        },
        removeImage() {
            this.thumb_upload = true;
            this.$refs.thumbnail.value = null;
            this.form.thumbnail = null;
            this.thumb_link = null;
        },
        titleText() {
            this.errors.title = "";
            document.getElementById("titleTemp").innerHTML = this.form.title;
        },
        summaryText() {
            this.errors.summary = "";
        },
        videoUploadStatus(event) {
            this.video_upload = false;
            let inputVideo = this.$refs.video;
            let file = inputVideo.files;
            var extension = file[0].type;
            let acceptedMimes = "m4v,avi,mpg,mp4,quicktime,x-matroska"
                .trim()
                .split(",")
                .map((extension) => `video/${extension}`);
            if (acceptedMimes.includes(extension)) {
                if (file && file[0]) {
                    this.fileName = file[0].name;
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.video_link = e.target.result;
                    };
                    reader.readAsDataURL(file[0]);
                }
                this.form.video = this.$refs.video.files[0];
                this.$emit("input", file[0]);

                this.errors.video = "";
                document.getElementById("submitVideo").disabled = false;
                document.getElementById("tempVideo").disabled = false;
            } else {
                this.errors.video = "動画ファイルのみ対応";
                this.fileName = "動画をアップロード";
                document.getElementById("submitVideo").disabled = true;
                document.getElementById("tempVideo").disabled = true;
            }
        },
    },
};
</script>
