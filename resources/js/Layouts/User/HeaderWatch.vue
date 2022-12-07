<template>
    <nav class="header video-watch">
        <div class="left-area">
            <button class="btn btn-link" @click="funcBack(this.courseDetails.id)">
                <img :src="'/images/icon-arrow-left.svg'" alt="" />戻る
            </button>
            <button id="hideBtn" class="btn btn-link" @click="funcHideNav(this.courseDetails.id)">

            </button>
        </div>
        <div class="right-area">
            <p class="title">{{ this.courseDetails.title }}</p>
        </div>
    </nav>
    <!-- <div @mouseover="upHere = true" @mouseleave="upHere = false" >
        <h2>Something</h2>
        <p v-show="upHere">asdasd</p>
    </div> -->
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import SidebarWatch from "../User/SidebarWatch.vue";

export default {
    components: {
        SidebarWatch,
    },
    data() {
        return {
            hide: false
        };
    },
    mounted() {

        if (window.location.pathname.split("/").length == 6 && window.location.pathname.split("/")[2] == 'videoPlay') {
            document.getElementById("hideBtn").innerHTML = `メニューを隠す<img src="/images/icon-close.svg" alt="" />`;
        } else if (window.location.pathname.split("/").length == 6 && window.location.pathname.split("/")[2] == 'videoFull') {
            document.getElementById("hideBtn").innerHTML = `メニューを表示<img src="/images/icon-arrow-rightVdeio.svg" alt="" />`;
        }

        if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'videosView') {
            document.getElementById("hideBtn").innerHTML = `メニューを隠す<img src="/images/icon-close.svg" alt="" />`
        } else if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'videosViewFull') {
            document.getElementById("hideBtn").innerHTML = `メニューを表示<img src="/images/icon-arrow-rightVdeio.svg" alt="" />`;
        }
        if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'report') {
            document.getElementById("hideBtn").innerHTML = `メニューを隠す<img src="/images/icon-close.svg" alt="" />`
        } else if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'reportFull') {
            document.getElementById("hideBtn").innerHTML = `メニューを表示<img src="/images/icon-arrow-rightVdeio.svg" alt="" />`;
        }
        if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'document') {
            document.getElementById("hideBtn").innerHTML = `メニューを隠す<img src="/images/icon-close.svg" alt="" />`
        } else if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'documentFull') {
            document.getElementById("hideBtn").innerHTML = `メニューを表示<img src="/images/icon-arrow-rightVdeio.svg" alt="" />`;
        }
        if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'worksheet') {
            document.getElementById("hideBtn").innerHTML = `メニューを隠す<img src="/images/icon-close.svg" alt="" />`
        } else if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'worksheetFull') {
            document.getElementById("hideBtn").innerHTML = `xxxxx<img src="/images/icon-arrow-rightVdeio.svg" alt="" />`;
        }

    },

    inject: ["courseDetails"],
    methods: {
        funcBack(id) {
            var SchoolId = 0;
            window.location.href = `/course/details/${id}`;
        },
        funcHideNav(id) {
            if (window.location.pathname.split("/").length == 6 && window.location.pathname.split("/")[2] == 'videoPlay') {
                Inertia.get(`/course/videoFull/${window.location.pathname.split("/")[3]}/${window.location.pathname.split("/")[4]}/${window.location.pathname.split("/")[5]}`);
            } else if (window.location.pathname.split("/").length == 6 && window.location.pathname.split("/")[2] == 'videoFull') {
                Inertia.get(`/course/videoPlay/${window.location.pathname.split("/")[3]}/${window.location.pathname.split("/")[4]}/${window.location.pathname.split("/")[5]}`);
            }

            if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'videosView') {
                Inertia.get(`/course/videosViewFull/${window.location.pathname.split("/")[3]}`);
            } else if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'videosViewFull') {
                Inertia.get(`/course/videosView/${window.location.pathname.split("/")[3]}`);
            }

            if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'report') {
                Inertia.get(`/course/reportFull/${window.location.pathname.split("/")[3]}`);
            } else if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'reportFull') {
                Inertia.get(`/course/report/${window.location.pathname.split("/")[3]}`);
            }

            if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'document') {
                Inertia.get(`/course/documentFull/${window.location.pathname.split("/")[3]}`);
            } else if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'documentFull') {
                Inertia.get(`/course/document/${window.location.pathname.split("/")[3]}`);
            }

            if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'worksheet') {
                Inertia.get(`/course/worksheetFull/${window.location.pathname.split("/")[3]}`);
            } else if (window.location.pathname.split("/").length == 4 && window.location.pathname.split("/")[2] == 'worksheetFull') {
                Inertia.get(`/course/worksheet/${window.location.pathname.split("/")[3]}`);
            }

        },
    },
};
</script>
