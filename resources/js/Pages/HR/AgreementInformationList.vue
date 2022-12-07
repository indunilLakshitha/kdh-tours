<template>
    <AppLayoutUser>
        <div class="user-layout">
            <Sidebar :username="username"></Sidebar>
            <div class="content agreement-information-list">
                <div class="page-container">
                    <div class="page-title-area">
                        <h1>契約者状況一覧</h1>
                        <div class="search-form">
                            <button class="btn btn-dark" @click="this.selectedStopUsing()">選択して利用停止</button>
                            <button class="btn btn-primary" @click="this.selectedResumeUsing()">選択して利用再開</button>
                        </div>
                    </div>
                    <div class="page-card scrollable">
                        <table class="table agreement-list">
                            <thead>
                            <tr>
                                <th>選択</th>
                                <th>ライセンスキー<span class="icon-sort"></span></th>
                                <th colspan="2">利用者<span class="icon-sort"></span></th>
                                <th>メールアドレス</th>
                                <th>ステータス</th>
                                <th>契約状況<span class="icon-sort"></span></th>
                                <th>リンク再送信</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(user,index) in users">
                                <td><input type="checkbox" class="form-check sml" :disabled="user.product_key_status=='Force-Stopped' || user.is_mine==1"
                                           @change="this.addProductId(user.product_id)"/></td>
                                <td v-text="user.product_key"></td>
                                <td v-text="user.name"></td>
                                <td v-text="user.username"></td>
                                <td v-text="user.email"></td>
                                <td>
                                    <div class="date">
                                        <span>利用開始：{{ user.issued_date }}</span>
                                        <span>使用期間：{{ user.expire_date }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="status not-use" v-if="user.product_key_status=='Force-Stopped'">
                                        強制停止
                                    </div>
                                    <div class="status use" v-if="user.product_key_status=='Active'">利用中</div>
                                    <div class="status not-use" v-if="user.product_key_status=='Expired'|| user.product_key_status=='Not-Active'">利用停止</div>
                                    <div class="status not-use" v-if="user.product_key_status=='Suspend-HRM'">利用停止</div>
                                </td>
                                <td>
                                    <button class="btn btn-dark" :disabled="user.status=='Active'"
                                            @click="this.sendInvitationLink(user.id)">リンク再送信
                                    </button>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Toast -->
        <div
            class="toast toast-success"
            v-if="alertVisible == 'visible' && message != null"
        >
            <div class="toast-body" v-text="message"></div>
            <img
                :src="'/images/icon-toast-close.svg'"
                class="btn-close"
                @click="this.closeAlert()"
            />
        </div>
        <!--  -->
    </AppLayoutUser>
</template>

<script>
import AppLayoutUser from "@/Layouts/AppLayoutUser.vue";
import {Inertia} from '@inertiajs/inertia';
import Sidebar from "@/Layouts/User/Sidebar.vue";

export default {
    components: {AppLayoutUser, Sidebar},
    props: {
        users: Object,
        username:String
    },
    // Modal
    data() {
        return {
            open: false,
            alertVisible: "!visible",
            message:null,
            form: {
                product_list: []
            },
            user :{
                id:null
            }
        }
    },
    methods: {
        goToCreate() {
            Inertia.get("/admin/course/create");
        },
        addProductId(productId) {
            // Get the index of id in the array
            const index = this.form.product_list.indexOf(productId);
            if (index > -1) {
                // This means id is present in the array, so remove it
                this.form.product_list.splice(index, 1);
            } else {
                // This means id is not present in the array, so add it
                this.form.product_list.push(productId);
            }
        },
        sendInvitationLink(id) {
            if(id!=null){
                this.user.id=id;
                Inertia.post(route('hr.user-email.resend'), this.user);

                this.alertVisible='visible';
                this.message ='会社アドレスにメールが届きます。';
                setInterval(() => {
                    this.closeAlert();
                }, 5000);
            }
        },
        selectedStopUsing() {
            if(this.form.product_list.length>0){
                Inertia.post(route('hr.license.stop'), this.form);
            }
            window.location.reload();
        },
        selectedResumeUsing() {
            if(this.form.product_list.length>0){
                Inertia.post(route('hr.license.resume'), this.form);
            }
            window.location.reload();
        },
        closeAlert() {
            this.alertVisible = "!visible";
        },
    }
};

// Collapse expand

</script>
