<template>
    <AppLayoutUser>
        <div class="user-layout">
            <div class="content full-width">
                <div class="page-container favorite-list">
                    <div class="title-area">
                        <h3>お気に入り一覧</h3>
                    </div>
                    <div class="favorite-section">
                        <!--  -->
                        <div class="favorite-single" v-for="(fav, index) in favs" :key="index">
                            <div class="thumb-wrapper">
                                <img :src="fav.course.thumbnail" alt="" />
                            </div>
                            <div class="content-area">
                                <div class="title-wrapper">
                                    <div class="title">{{ fav.course.title }}</div>
                                    <img :src="'/images/icon-bookmark-fill.svg'" @click="addToFav(fav.course.id)"
                                        class="icon-bookmark" alt="" />
                                    <!-- <img :src="'/images/icon-bookmark.svg'" class="icon-bookmark" alt="" /> -->
                                </div>
                                <div class="two-col-area">
                                    <div class="favorite-left">
                                        <div class="info-area">
                                            <span> <img src="/images/icon-clock.svg"
                                                    alt="" />{{ Math.round(fav.course.total_watchtime / 60) }} m</span>
                                            <span> <img src="/images/icon-tag.svg"
                                                    alt="" />{{ fav.course.course_has_categories[0].category.name }}
                                            </span>
                                        </div>
                                        <div class="seekbar-area-wrapper">
                                            <label for="">進捗状況</label>
                                            <div class="seekbar-area">
                                                <div class="seekbar-wrapper">
                                                    <div class="seekbar" :style="'width:' + fav.percent + '%'"></div>
                                                </div>
                                                <label class="completed">{{ fav.percent }}<small>%</small></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="favorite-right">
                                        <button class="btn btn-dark" @click="goToCourse(fav.course.id)">もっと見る</button>
                                        <button v-if="fav.course.first_video" class="btn btn-primary"
                                            @click="goToCourseView(fav.course.first_video.id, fav.course.first_video.vedio_id, fav.course.id)">視聴する</button>
                                        <button v-if="!fav.course.first_video" class="btn btn-primary"
                                            @click="goToCourseView(null, null, fav.course.id)">視聴する</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->

                        <!--  -->
                    </div>
                </div>
            </div>
        </div>
    </AppLayoutUser>
</template>

<script>
import AppLayoutUser from "@/Layouts/AppLayoutUser.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";

export default {
    props: {
        favs: Object,
    },
    components: { AppLayoutUser, Link },
    data() {
        return {
            fav_ids: []
        };
    },
    created() {

    },
    methods: {
        goToCourse(id) {
            Inertia.get("/course/details/" + id);
        },
        goToCourseView(tId, vId, id) {
            if (tId != null) {
                Inertia.get(`/course/videoPlay/${tId}/${vId}/${id}`);
            } else {
                Inertia.get(`/course/videosView/${id}`);

            }
        },
        addToFav(course_id) {
            console.log(course_id);
            let user_token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            axios
                .post("/favourites", {
                    course_id: course_id,
                    headers: {
                        _token: user_token,
                    },
                })
                .then((response) => {
                    location.reload();
                });
        },
    }
};
</script>
