<template>
    <AppLayoutAdmin>
        <div class="content license-key-details">
            <div class="page-title-area">
                <h1>ライセンスキー詳細</h1>
            </div>
            <div class="page-card">
                <div class="card-row">
                    <label for="" class="lrg">ライセンスキー</label>
                    <span class="value lrg" v-text="user.product_key"></span>
                    <div class="align-right">
                        <label class="sml">ステータス選択</label>
                        <select class="form-select" @change="switchSelect($event)">
                            <option :value="'Suspend-HRM'" :selected="user.product_key_status=='Active' || user.product_key_status=='Expired' || user.product_key_status=='Suspend-HRM'">利用可能</option>
                            <option :value="'Force-Stopped'" :selected="user.product_key_status=='Force-Stopped'">強制停止</option>
                        </select>

                    </div>
                </div>
                <section class="collapse-section">
                    <div class="card-row collapse-header">
                        登録情報
                        <div class="align-right">
                            <img :src="'/images/icon-select-down-arrow.svg'" class="down" alt="" />
                        </div>
                    </div>
                    <div class="collapse-content">
                        <div class="card-row">
                            <label>名前</label>
                            <span class="value" v-text="user.name"></span>
                        </div>
                        <div class="card-row">
                            <label>ふりがな</label>
                            <span class="value" v-text="user.furigana"></span>
                        </div>
                        <div class="card-row">
                            <label>会社名</label>
                            <span class="value" v-text="user.company_name"></span>
                        </div>
                        <div class="card-row">
                            <label>役職</label>
                            <span class="value" v-text="user.position"></span>
                        </div>
                        <div class="card-row">
                            <label>メールアドレス</label>
                            <span class="value" v-text="user.email"></span>
                            <div class="align-right">
                                <button class="btn btn-email">
                                    <img :src="'/images/icon-email.svg'" alt="">
                                    メールを送る
                                </button>
                            </div>
                        </div>
                        <div class="card-row">
                            <label>ニックネーム</label>
                            <span class="value" v-text="user.nick_name"></span>
                        </div>
                        <div class="card-row">
                            <label>パスワード</label>
                            <span class="value">＊＊＊＊＊＊＊＊＊＊＊</span>
                        </div>
                    </div>
                </section>
                <section class="collapse-section">
                    <div class="card-row collapse-header">
                        契約状況
                        <div class="align-right">
                            <img :src="'/images/icon-select-down-arrow.svg'" class="down" alt="" />
                        </div>
                    </div>
                    <div class="collapse-content">
                        <div class="card-row">
                            <label>利用開始</label>
                            <span class="value" v-text="user.issued_date"></span>
                        </div>
                        <div class="card-row">
                            <label>更新時期</label>
                            <span class="value" v-text="user.expire_date"></span>
                        </div>
                        <div class="card-row">
                            <label>契約形態</label>
                            <span class="value" v-text="user.contract_type"></span>
                        </div>
                        <div class="card-row">
                            <label>支払い方法</label>
                            <span class="value">請求書</span>
                        </div>
                        <div class="card-row">
                            <label>支払い状況</label>
                            <span class="value">
                                <div class="check-area">
                                    <input type="radio" class="form-check sml" name="r1" :checked="user.payment_status==1" :value="1" v-model="form.payment_status"/><label>確認済み</label>
                                    <input type="radio" class="form-check sml" name="r1"  :checked="user.payment_status==0" :value="0" v-model="form.payment_status"/><label>未確認</label>
                                </div>
                            </span>
                        </div>
                        <div class="card-row baseline">
                            <label>コンタクト</label>
                            <span class="form-group value">
                                <textarea v-model="form.contact" v-text="this.form.contact" class="form-control" placeholder="申し訳ありませんが、お支払いが確認できておりません。
お手数をおかけいたしますが、ご登録メールアドレスに案内を送らせていただいておりますので、ご確認をよろしくお願いいたします。
なおステータス反映まで数日時間がかかりますので、行き違いになっておりましたらご了承ください。"></textarea>
                            </span>
                        </div>
                    </div>
                </section>
                <section class="collapse-section">
                    <div class="card-row collapse-header">
                        アプローチ
                        <div class="align-right">
                            <img :src="'/images/icon-select-down-arrow.svg'" class="down" alt="" />
                        </div>
                    </div>
                    <div class="collapse-content">
                        <div class="card-row">
                            <label>学習プラン提案</label>
                            <span class="value">
                                <div class="check-area">
                                    <input type="radio" class="form-check sml" name="r2" :checked="user.proposal_status==1" :value="1" v-model="form.proposal"/><label for="">提案済み</label>
                                    <input type="radio" class="form-check sml" name="r2" :checked="user.proposal_status==0" :value="0" v-model="form.proposal"/><label for="">未提案</label>
                                </div>
                            </span>
                        </div>
                        <div class="card-row">
                            <label>&nbsp;</label>
                            <span class="form-group value flex-column">
                                <div class="file-upload-wrapper" v-if="this.proposal_file_name==null">
                                    <input type="file" class="form-control file-upload file" ref="proposal_site" @change="onFileChange"/>
                                    <label for="">ファイルをアップロード</label>
                                </div>
                                <div class="input-row"  v-if="this.proposal_file_name!=null">
                                    <input type="text" class="form-control" :value="this.proposal_file_name" disabled/>
                                    <img :src="'/images/icon-close-circle.svg'" class="icon-close" alt="" @click="removeFile()"/>
                                </div>
                            </span>
                        </div>
                    </div>
                </section>
            </div>
            <div class="btn-area">
                <button class="btn btn-secondary" @click="cancel()">キャンセル</button>
                <button class="btn btn-primary" @click="update()">更新</button>
            </div>
        </div>
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
    props:{
      user:Object,
    },
      data() {
      return {
        open: false,
          proposal_file_name:this.user.proposal_file_name,
          isLoading:false,
          form:{
              payment_status:this.user.payment_status,
              contact:this.user.contact,
              proposal:this.user.proposal_status,
              status:this.user.status,
              product_id:this.user.product_id,
              file:null
          }
      };
    },
      methods:{
          cancel(){
              window.location.reload();
          },
          update(){
              this.isLoading=true;
              if (this.$refs.proposal_site != null) {
                  this.form.file = this.$refs.proposal_site.files[0];
              }
              Inertia.post(route('admin.lisensekey.update'),this.form);
          },
          sendEmail(){

          },
          removeFile(){
            this.proposal_file_name=null;
          },
          switchSelect(event) {
              this.form.status = event.target.value;
          },
          onFileChange(e) {
              this.form.file = this.$refs.proposal_site.files[0];
              this.proposal_file_name = this.$refs.proposal_site.files[0].name
          },
      },
      mounted() {
      }
  };
  </script>
