<template>
    <AppLayoutWatch>
        <div class="user-layout bg-white">
            <SidebarWatch />
            <div class="content video-view-5">
                <h2 class="page-sub-title">{{this.user.name}}</h2>
                <h1 class="page-title">達成おめでとうございます！</h1>
                <section>
                    <div class="avatar">
                        <img
                            :src="'https://cdn-icons-png.flaticon.com/512/1177/1177568.png'"
                            alt=""
                        />
                        <span>イラスト</span>
                    </div>
                    <button class="btn btn-primary" @click="func(this.courseId)">
                        受講状況を見る
                        <img :src="'/images/icon-right.svg'" alt="" />
                    </button>
                    <div class="gray-content">
                        <p>ダウンロードして、アウトプットにご活用ください。</p>
                        <div
                            v-for="(row, index) in this.postViewings"
                            :key="index"
                        >
                            <div class="download-row" v-if="row.type == 1">
                                <span class="title">資料ダウンロード</span>
                                <span class="description">{{ row.title }}</span>
                                <img
                                    :src="'/images/icon-download.svg'"
                                    alt=""
                                    @click.prevent="
                                                downloadItem(row.document)
                                            "
                                />
                            </div>
                            <div class="download-row" v-if="row.type == 0">
                                <span class="title">ワークシート</span>
                                <span class="description">{{ row.title }}</span>
                                <img
                                    :src="'/images/icon-download.svg'"
                                    alt=""
                                    @click.prevent="
                                                downloadItem(row.document)
                                            "
                                />
                            </div>

                        </div>
                        <div class="download-row" v-if="this.documents == 0">
                            <span class="title centered">資料はありません。</span>
                        </div>
                        <div class="download-row" v-if="this.worksheet == 0">
                            <span class="title centered">ワークシートはありません。</span>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AppLayoutWatch>
</template>

<script>
import AppLayoutWatch from "@/Layouts/AppLayoutWatch.vue";
import { Inertia } from "@inertiajs/inertia";
import SidebarWatch from "../../../Layouts/User/SidebarWatch.vue";

export default {
    components: { AppLayoutWatch, SidebarWatch },
    props: {
        courseDetails: Object,
        worksheet:Number,
        documents: Number,
        user: Object
    },
    provide() {
        return { courseDetails: this.courseDetails };
    },

    // Modal
    data() {
        return {
            open: false,
            form: {
                email: null,
                password: null,
            },
            postViewings: Object,
            courseId: null,
        };
    },
    created() {
        this.postViewings = this.courseDetails.post_viewings;
        this.courseId = this.courseDetails.id;
        console.log(this.documents);
    },
    methods: {
        goToCreate() {
            Inertia.get("/admin/course/create");
        },
        downloadItem(url) {
            window.open(
                url,
                '_blank' // <- This is what makes it open in a new window.
            );
        },
        func(id) {
            var SchoolId = 0;
            window.location.href = `/my-page`;
        },
    },
};
</script>
