<template>
    <AppLayoutAdmin>
        <div class="content license-key-list">
            <div class="page-title-area">
                <h1>ライセンスキー管理</h1>
                <div class="search-form">
                    <input type="text" class="form-control" placeholder="ライセンスキー検索" />
                    <button class="btn btn-dark">一括ダウンロード</button>
                </div>
            </div>
            <div class="page-card scrollable">
                <table class="table license-key-table">
                    <thead>
                        <tr>
                            <th>ライセンスキー<span class="icon-sort"></span></th>
                            <th>名前</th>
                            <th>ふりがな</th>
                            <th>会社名</th>
                            <th>契約形態</th>
                            <th>利用開始<span class="icon-sort"></span></th>
                            <th>使用期間<span class="icon-sort"></span></th>
                            <th>提案書<span class="icon-sort"></span></th>
                            <th>ステータス<span class="icon-sort"></span></th>
                            <th>支払い状態<span class="icon-sort"></span></th>
                            <th>編集</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user,index) in users">
                            <td v-text="user.product_key"></td>
                            <td v-text="user.name"></td>
                            <td v-text="user.furigana"></td>
                            <td>
                                <div class="d-block">
                                    <span v-text="user.company_name"></span>
                                    <small v-if="user.user_type==1">担当者</small>
                                    <small v-if="user.user_type==2">利用者</small>
                                </div>
                            </td>
                            <td v-if="user.user_type==1">担当者</td>
                            <td v-if="user.user_type==2">利用者</td>
                            <td v-text="user.issued_date"></td>
                            <td v-text="user.expire_date"></td>
                            <td>
                                 <img :src="'/images/icon-check-circle.svg'" alt="" v-if="user.proposal_status==1"/>
                                <img :src="'/images/icon-close-fill.svg'" alt="" v-if="user.proposal_status==0"/>
                            </td>
                            <td>
                                <img :src="'/images/icon-check-circle.svg'" alt=""  v-if="user.product_key_status=='Active' || user.product_key_status=='Suspend-HRM'"/>
                                <img :src="'/images/icon-close-fill.svg'" alt="" v-if="user.product_key_status=='Force-Stopped' || user.product_key_status=='Expired'"/>
                            </td>
                            <td>
                                <img :src="'/images/icon-check-circle.svg'" alt="" v-if="user.payment_status==1"/>
                                <img :src="'/images/icon-close-fill.svg'" alt="" v-if="user.payment_status==0"/>
                            </td>
                            <td>
                                <button class="btn btn-dark" @click="edit(user.id)">編集</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayoutAdmin>
</template>

<script>
import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin.vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";

export default {
    components: { AppLayoutAdmin },
    props:{
      users:Object
    },
    data() {
        return {
            open: false,
        };
    },
    methods:{
        edit(userId){
            Inertia.get(route('admin.lisensekey.edit'),{
                'id':userId
            });
        }
    }
};
</script>
