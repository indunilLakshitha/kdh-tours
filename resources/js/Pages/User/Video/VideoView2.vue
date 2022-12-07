<template>
    <AppLayoutWatch>
        <div class="user-layout bg-white">
            <SidebarWatch :key="renderKey" />

            <div class="content video-view-2">
                <!-- <img :src="'images/video/video2.png'" class="video" alt="" /> -->
                <iframe id="videoIframe" width="1280" height="720" controls="false" allowfullscreen
                    allow="autoplay; encrypted-media" src=""></iframe>
            </div>
        </div>
    </AppLayoutWatch>
</template>

<script>
import AppLayoutWatch from "@/Layouts/AppLayoutWatch.vue";
import { Inertia } from "@inertiajs/inertia";
import SidebarWatch from "../../../Layouts/User/SidebarWatch.vue";
import HeaderWatch from "../../../Layouts/User/HeaderWatch.vue";
import Player from "@vimeo/player";
import { useForm } from "@inertiajs/inertia-vue3";
import axios from "axios";

export default {
    components: { AppLayoutWatch, HeaderWatch, SidebarWatch },
    props: {
        courseDetails: Object,
        video: Object,
        watchedVideo: Object,
        nextVideo: Object,
        nextVideoTitleDetails: Object,
        firstVideoTitleDetails: Object,
        lastVideo: Boolean,
    },
    // Modal
    data() {
        return {
            form: useForm({
                title: "",
                summary: "",
                video: null,
                thumbnail: null,
                status: "",
            }),
            open: false,
            title: this.courseDetails.title,
            courseDuration: null,
            courseDurationCount: null,
            courseDurationHours: null,
            courseDurationMinutes: null,
            courseCategory: "",
            courseReport: false,
            courseThumbnail: "",
            courseSummery: "",
            courseTitlesAndVideo: Object,
            courseId: null,
            courseActivity: [],
            videoId: null,
            renderKey: 0,
        };
    },
    provide() {
        return { courseDetails: this.courseDetails };
    },
    created() { },
    mounted() {
        this.startPlaying();
    },

    methods: {
        // goToCreate() {
        //     Inertia.get("/admin/course/create");
        // },

        playVideos() {
            alert("this is A.foo");
        },
        foo() {
            alert("this is A.foo");
        },
        startPlaying() {
            if (this.watchedVideo) {
                if (this.watchedVideo.status == 1) {
                    document.getElementById(
                        "videoIframe"
                    ).src = `https://player.vimeo.com/video/${this.video.videos.split("/")[2]
                        }?portrait=0&title=0&byline=0&speed=true&#t=${this.watchedVideo.total_watch_time
                        }`;
                } else {
                    document.getElementById(
                        "videoIframe"
                    ).src = `https://player.vimeo.com/video/${this.video.videos.split("/")[2]
                        }?portrait=0&title=0&byline=0&speed=true`;
                }
            } else {
                document.getElementById(
                    "videoIframe"
                ).src = `https://player.vimeo.com/video/${this.video.videos.split("/")[2]
                    }?portrait=0&title=0&byline=0&speed=true&`;
            }

            var iframe = document.querySelector("iframe");
            var player = new Player(iframe);

            player.on("pause", () => {
                player
                    .getCurrentTime()
                    .then((seconds) => {
                        axios
                            .post(route("course.watchTime"), {
                                time: seconds,
                                videoId: this.video.id,
                                courseId: this.courseDetails.id,
                            })
                            .then((response) => {
                                if (response.data[0] == "Finished") {
                                    //window.location.href = `/course/document/${this.courseDetails.id}`;
                                }

                                if (this.lastVideo != true) {
                                    if (response.data[1] == 0) {
                                        Inertia.get(
                                            `/course/videoPlay/${this.nextVideoTitleDetails.id}/${this.nextVideoTitleDetails.vedio_id}/${this.courseDetails.id}`
                                        );
                                    }
                                } else if (this.lastVideo == true) {
                                    Inertia.get(`/course/report/${this.courseDetails.id}`);
                                } else {
                                }
                            });
                    })
                    .catch(function(error) {
                        // an error occurred
                    });
            });
        },
    },
};
</script>
