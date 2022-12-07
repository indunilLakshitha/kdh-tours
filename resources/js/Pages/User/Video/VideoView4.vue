<template>
    <AppLayoutWatch>
        <div class="user-layout bg-white">
            <SidebarWatch />
            <div class="content video-view-4">
                <h1 class="page-title">レポート提出</h1>
                <div class="border-box">
                    <div v-for="(row, index) in this.report" :key="index">
                        <section v-if="row['type'] == 0">
                            <div class="title">{{ row["title"] }}</div>
                            <p>{{ row["description"] }}</p>
                            <div class="form-group">
                                <textarea v-model="this.reportId[row['id']]" name="" id="" :key="index"
                                    class="form-control" @keyup="textCounter(row['id'])"></textarea>
                                <div class="char-count-report">
                                    <div :id="'textCounter' + row['id']" class="char-count">
                                        0/500文字以内
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div v-for="(row, index) in this.report" :key="index">
                        <div v-if="row['type'] == 1">
                            <section>
                                <div class="title">{{ row["title"] }}</div>
                                <p>{{ row["description"] }}</p>
                                <div class="form-group">
                                    <div class="download-area">
                                        <span class="description">{{
                                                row["title"]
                                        }}</span>
                                        <img :src="'/images/icon-download.svg'" alt="" class="" @click.prevent="
                                            downloadItem(row['document'])
                                        " />
                                    </div>
                                </div>
                            </section>
                            <section>
                                <div class="title">資料アップロード</div>
                                <p></p>
                                <div class="form-group d-flex">
                                    <div class="file-upload-wrapper">
                                        <input type="file" :id="'reportFile-' + row['id']" @change="
                                            reportFileUploadStatus(
                                                $event,
                                                row['id']
                                            )
                                        " class="form-control file-upload file" />
                                        <label for="">ファイルをアップロード</label>
                                        <div class="spinner-wrapper" v-if="
                                            this.checkUploadSpinner.find(
                                                (item) => item === row['id']
                                            )
                                        ">
                                            <div class="lds-dual-ring"></div>
                                        </div>
                                        <div class="error" :id="'reportFileError-' + row['id']"></div>
                                    </div>
                                </div>
                                <div class="title-closable" v-if="
                                    this.checkUploadDocuments.includes(
                                        row['id']
                                    )
                                ">
                                    <span>
                                        {{
                                                this.form.reportDocuments.find(
                                                    (item) => item.id === row["id"]
                                                ).name
                                        }}
                                    </span>
                                    <img :src="'/images/icon-close-circle.svg'" alt="" class="btn-close" @click="
                                        reportFileRemoveStatus(row['id'])
                                    " />
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <p class="text-red">
                    ※仮保存したレポート提出画面はマイアカウントから編集できます。
                </p>
                <p class="para">上記の内容で送信しますか？</p>
                <div class="btn-area">
                    <button class="btn btn-secondary" id="tempReport" @click="submitReport(0)">
                        仮保存
                    </button>
                    <button class="btn btn-primary" id="submitReport" @click="submitReport(1)">
                        送信
                    </button>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <div class="toast toast-success" v-if="successToast" sty>
            <div class="toast-body">
                {{this.msg}}</div>
            <img
                :src="'/images/icon-toast-close.svg'"
                class="btn-close"
                @click="this.successToast = false"
            />
        </div>

        <div class="spinner-wrapper" v-if="isLoading">
            <div class="lds-dual-ring"></div>
        </div>
    </AppLayoutWatch>
</template>

<script>
import AppLayoutWatch from "@/Layouts/AppLayoutWatch.vue";
import { Inertia } from "@inertiajs/inertia";
import SidebarWatch from "../../../Layouts/User/SidebarWatch.vue";
import HeaderWatch from "../../../Layouts/User/HeaderWatch.vue";
import axios from "axios";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    components: { AppLayoutWatch, HeaderWatch, SidebarWatch },
    props: {
        courseDetails: Object,
    },
    provide() {
        return { courseDetails: this.courseDetails };
        // this.courseId = this.courseDetails.id= this.courseDetails.id;
        // dispatch('SidebarWatch', this.courseDetails);
    },
    // Modal
    data() {
        return {
            open: false,
            form: useForm({
                reportDocuments: [],
            }),
            reportId: [],
            reportText: [],
            reportTextIds: [],
            checkUploadDocuments: [],
            checkUploadSpinner: [],
            report: Object,
            isLoading: false,
            existReport: Object,
            submitBtnHandle: false,
            completedVideoCount: 0,
            successToast: false,
            msg:null
        };
    },
    created() {
        this.report = this.courseDetails.reports;
        this.report.forEach((element) => {
            if (element.type === 0) {
                this.reportTextIds.push(element.id);
            }
        });

        if (this.courseDetails.auth_user_course.course_activities) {
            this.courseDetails.auth_user_course.course_activities.forEach(
                (element) => {
                    console.log(element);
                    if (element["status"] == 0) {
                        this.completedVideoCount = this.completedVideoCount + 1;
                    }
                }
            );
        }

        if (this.courseDetails.auth_user_course.report_activities) {
            this.existReport =
                this.courseDetails.auth_user_course.report_activities.report_activity_details;
            this.existReport.forEach((element) => {
                if (element["type"] == 0) {
                    this.reportId[element["course_report_id"]] =
                        element["content"];
                } else if (element["type"] == 1 && element["document"]) {
                    this.form.reportDocuments.push({
                        id: element["course_report_id"],
                        url: element["document"],
                        name: element["document"].split(
                            `reports/${this.courseDetails.id}/`
                        )[1],
                    });
                    this.checkUploadDocuments.push(element["course_report_id"]);
                }
            });

            if (
                this.courseDetails.auth_user_course.report_activities.status ==
                1 &&
                this.completedVideoCount == this.courseDetails.total_vedio_count
            ) {
                this.submitBtnHandle = true;
            } else {
                var countExistRep = 0;
                this.existReport.forEach(exist => {
                    if (exist['status'] == 1) {
                        countExistRep = countExistRep + 1;
                    }
                });
                console.log(this.courseDetails.reports.length, countExistRep );
                console.log(this.completedVideoCount,this.courseDetails.total_vedio_count);
                if (this.courseDetails.reports.length == countExistRep && this.completedVideoCount == this.courseDetails.total_vedio_count) {
                    this.submitBtnHandle = true;
                }
            }
        }

    },
    mounted() {
        if (this.submitBtnHandle == false) {
            document.getElementById("submitReport").disabled = true;
        } else {
            document.getElementById("submitReport").disabled = false;
        }
    },
    methods: {
        goToCreate() {
            Inertia.get("/admin/course/create");
        },
        textCounter(id) {
            var max = this.reportId[id];

            if (max.length > 500) {
                document.getElementById(
                    `textCounter${id}`
                ).innerHTML = `<span>${max.length}/500文字以内</span>`;
            } else {
                document.getElementById(
                    `textCounter${id}`
                ).innerHTML = `${max.length}/500文字以内</span>`;
            }
            var check = 0;
            var textarea = document.getElementsByTagName("textarea");
            var inputs = document.getElementsByTagName("input");

            for (var index = 0; index < textarea.length; ++index) {
                if (textarea[index].value == "") {
                    check = check + 1;
                }
            }
            for (var index = 0; index < inputs.length; ++index) {
                if (inputs[index]) {
                    var inputId = inputs[0].id;
                    var fixId = parseInt(inputId.split("-")[1]);
                    if (inputs[index].value == "") {
                        if (
                            !this.form.reportDocuments.find(
                                (item) => item.id == fixId
                            )
                        ) {
                            check = check + 1;
                        }
                    }
                }
            }
            if (check > 0) {
                document.getElementById("submitReport").disabled = true;
            } else if (
                check == 0 &&
                this.completedVideoCount == this.courseDetails.total_vedio_count
            ) {
                document.getElementById("submitReport").disabled = false;
            }
        },
        submitVideoPublish() { },
        downloadItem(url) {
            window.open(
                url,
                '_blank' // <- This is what makes it open in a new window.
            );
        },
        reportFileUploadStatus(event, id) {
            document.getElementById("submitReport").disabled = true;
            document.getElementById("tempReport").disabled = true;
            let formData = new FormData();
            formData.append("document", event.target.files[0]);
            let user_token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            if (event.target.files[0].size < 5242880) {
                this.checkUploadSpinner.push(id);
                document.getElementById(`reportFileError-${id}`).innerHTML = "";
                axios
                    .post(route("course.reportDocumentSave"), formData, {
                        headers: {
                            _token: user_token,
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then((response) => {
                        if (response.status == 200) {
                            if (this.checkUploadDocuments.indexOf(id)) {
                                const indexCheck =
                                    this.checkUploadDocuments.indexOf(id);
                                const xCheck = this.checkUploadDocuments.splice(
                                    indexCheck,
                                    1
                                );
                            }
                            if (
                                this.form.reportDocuments.find(
                                    (item) => item.id === id
                                )
                            ) {
                                const itemReport =
                                    this.form.reportDocuments.find(
                                        (item) => item.id === id
                                    );
                                const indexReport =
                                    this.form.reportDocuments.indexOf(
                                        itemReport
                                    );
                                const xReport =
                                    this.form.reportDocuments.splice(
                                        indexReport,
                                        1
                                    );
                            }
                            this.form.reportDocuments.push({
                                id: id,
                                url: response.data.url,
                                name: event.target.files[0].name,
                            });
                            this.checkUploadDocuments.push(id);
                            if (
                                this.completedVideoCount ==
                                this.courseDetails.total_vedio_count
                            ) {
                                document.getElementById(
                                    "submitReport"
                                ).disabled = false;
                            } else {
                                document.getElementById(
                                    "tempReport"
                                ).disabled = true;
                            }

                            document.getElementById(
                                "tempReport"
                            ).disabled = false;
                            const indexCheckSpinner =
                                this.checkUploadSpinner.indexOf(id);
                            const xCheckSpinner =
                                this.checkUploadSpinner.splice(
                                    indexCheckSpinner,
                                    1
                                );

                            var check = 0;
                            var textarea =
                                document.getElementsByTagName("textarea");
                            var inputs = document.getElementsByTagName("input");

                            for (
                                var index = 0;
                                index < textarea.length;
                                ++index
                            ) {
                                if (textarea[index].value == "") {
                                    check = check + 1;
                                }
                            }
                            for (
                                var index = 0;
                                index < inputs.length;
                                ++index
                            ) {
                                if (inputs[index]) {
                                    var inputId = inputs[0].id;
                                    var fixId = parseInt(inputId.split("-")[1]);
                                    if (inputs[index].value == "") {
                                        if (
                                            !this.form.reportDocuments.find(
                                                (item) => item.id == fixId
                                            )
                                        ) {
                                            check = check + 1;
                                        }
                                    }
                                }
                            }
                            if (check > 0) {
                                document.getElementById(
                                    "submitReport"
                                ).disabled = true;
                            } else if (
                                check == 0 &&
                                this.completedVideoCount ==
                                this.courseDetails.total_vedio_count
                            ) {
                                document.getElementById(
                                    "submitReport"
                                ).disabled = false;
                            }
                        }
                    })
                    .catch(function(error) { });
            } else {
                document.getElementById(`reportFileError-${id}`).innerHTML =
                    "5MB以内にしてください。";
                document.getElementById("submitReport").disabled = true;
                document.getElementById("tempReport").disabled = true;
            }
        },
        reportFileRemoveStatus(id) {
            const indexCheck = this.checkUploadDocuments.indexOf(id);
            const xCheck = this.checkUploadDocuments.splice(indexCheck, 1);
            const itemReport = this.form.reportDocuments.find(
                (item) => item.id === id
            );
            const indexReport = this.form.reportDocuments.indexOf(itemReport);
            const xReport = this.form.reportDocuments.splice(indexReport, 1);

            var check = 0;
            var textarea = document.getElementsByTagName("textarea");
            var inputs = document.getElementsByTagName("input");

            for (var index = 0; index < textarea.length; ++index) {
                if (textarea[index].value == "") {
                    check = check + 1;
                }
            }
            for (var index = 0; index < inputs.length; ++index) {
                if (inputs[index]) {
                    var inputId = inputs[0].id;
                    var fixId = parseInt(inputId.split("-")[1]);
                    if (inputs[index].value == "") {
                        if (
                            !this.form.reportDocuments.find(
                                (item) => item.id == fixId
                            )
                        ) {
                            check = check + 1;
                        }
                    }
                }
            }
            if (check > 0) {
                document.getElementById("submitReport").disabled = true;
            } else if (
                check == 0 &&
                this.completedVideoCount == this.courseDetails.total_vedio_count
            ) {
                document.getElementById("submitReport").disabled = false;
            }
        },
        submitReport(status) {
            this.reportTextIds.length;
            this.reportText.splice(0, this.reportText.length);
            this.reportTextIds.forEach((element) => {
                this.reportText.push({
                    id: element,
                    text: this.reportId[element],
                });
            });
            this.isLoading = true;
            axios
                .post(route("course.reportSave"), {
                    text: this.reportText,
                    documents: this.form.reportDocuments,
                    status: status,
                    courseId: this.courseDetails.id,
                })
                .then((response) => {
                    this.isLoading = false;
                    if (status == 1) {
                        window.location.href = `/course/document/${this.courseDetails.id}`;
                    } else {
                        this.successToast = true;
                        this.msg = '仮保存しました。';
                        setTimeout(() => (this.successToast = false), 3000);
                    }
                })
                .catch(function(error) {
                    this.isLoading = false;
                });
        },
        tempSubmitReport() { },
    },
};
</script>
