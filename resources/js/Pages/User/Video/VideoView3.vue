<template>
    <AppLayoutWatch>
        <div class="user-layout bg-white">
            <div class="content video-view-3">
                <iframe  id="videoIframe" width="1280" height="720" controls="false" allowfullscreen
                    allow="autoplay; encrypted-media" src=""></iframe>
            </div>
        </div>
    </AppLayoutWatch>
</template>

<script>
import AppLayoutWatch from "@/Layouts/AppLayoutWatch.vue";
import { Inertia } from "@inertiajs/inertia";
import HeaderWatch from "../../../Layouts/User/HeaderWatch.vue";

export default {
    components: { AppLayoutWatch, HeaderWatch },
    props: {
        courseDetails: Object,
        video: Object,
        watchedVideo: Object,
        nextVideo: Object,
        nextVideoTitleDetails: Object,
        lastVideo: Boolean
    },
    // Modal
    data() {
        return {

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
            renderKey: 0

        };
    },
    provide() {
        return { courseDetails: this.courseDetails };
    },
    created() {

    },
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
                    }?portrait=0&title=0&byline=0&speed=true&#t=${this.watchedVideo.total_watch_time}`;
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
                                            `/course/videoFull/${this.nextVideoTitleDetails.id}/${this.nextVideoTitleDetails.vedio_id}/${this.courseDetails.id}`
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
