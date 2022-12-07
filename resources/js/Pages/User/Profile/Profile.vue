<template>
    <AppLayoutUser>
        <div class="user-layout">
            <Sidebar/>
            <div class="content profile">
                <form @submit.prevent="submit">
                    <div class="page-title-area">
                        <h1>ユーザー設定の修正変更</h1>
                    </div>

                    <div class="page-card">
                        <div class="form-area">
                            <div class="form-group">
                                <label for="">名前</label>
                                <div class="group-block">
                                    <input type="text" class="form-control" v-model="form.name"/>
                                    <div class="error" v-if="$page.props.errors.name"
                                         v-text="$page.props.errors.name"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">ふりがな</label>
                                <div class="group-block">
                                    <input type="text" class="form-control" v-model="form.furigana"/>
                                    <div class="error" v-if="$page.props.errors.furigana"
                                         v-text="$page.props.errors.furigana"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">会社名</label>
                                <div class="group-block">
                                    <input type="text" class="form-control" v-model="form.company_name" :readonly="user_data.user_type!=1"/>
                                    <div class="error" v-if="$page.props.errors.company_name"
                                         v-text="$page.props.errors.company_name"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">役職</label>
                                <div class="group-block">
                                    <select class="form-select" v-model="form.position">
                                        <option v-for="(position,index) in positions" :key="index" :value="position.id"
                                                :selected="form.position==position.id">
                                            {{ position.name }}
                                        </option>
                                    </select>
                                    <div class="error" v-if="$page.props.errors.position"
                                         v-text="$page.props.errors.position"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">メールアドレス</label>
                                <div class="group-flex">
                                    <input type="text" class="form-control mr-5" v-model="form.email" readonly/>
                                    <button class="btn btn-secondary-outline sml" disabled>メールアドレスの変更はこちら</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">
                                    ニックネーム
                                    <!--                                <div class="char-count">30文字以内</div>-->
                                </label>
                                <div class="group-block">
                                    <input type="text" class="form-control" v-model="form.nickname"/>
                                    <div class="error" v-if="$page.props.errors.nickname"
                                         v-text="$page.props.errors.nickname"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">パスワード</label>
                                <div class="group-flex">
                                    <input type="text" class="form-control" value="***************" readonly/>
                                    <button class="btn btn-secondary-outline sml" type="button" @click="showPassword">
                                        パスワードの変更はこちら
                                    </button>
                                </div>
                            </div>
                            <!-- Toggle area -->
                            <div class="toggle-area mb-7" v-if="isPasswordVisble">
                                <div class="form-group">
                                    <label for="">新しいパスワード</label>
                                    <div class="group-block">
                                        <input type="password" class="form-control" v-model="form.password"/>
                                        <div class="error" v-if="$page.props.errors.password"
                                             v-text="$page.props.errors.password"></div>
                                        <div>
                                            <div class="helper-text">
                                                文字数6～15字かつ大文字、小文字、数字、#?!@$%^&*-の組み合わせで入力してください。
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label :style="'margin-top: 0'" for="">新しいパスワード<br/>を再入力</label>
                                    <div class="group-block">
                                        <input type="password" class="form-control" v-model="form.confirm_password"/>
                                        <div class="error" v-if="$page.props.errors.confirm_password"
                                             v-text="$page.props.errors.confirm_password"></div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="form-group">
                                <label for="">プロフィール写真</label>
                                <div class="">
                                    <div class="group-flex">
                                        <div class="file-upload-wrapper"  v-if="!isImgVisible">
                                               <input type="file" class="form-control file-upload" ref="avatar"
                                                      id="upload-btn"  @change="onFileChange"  accept="image/jpg, image/jpeg, image/png,image/gif,image/svg"/>
                                                  <label>写真をアップロード</label>
                                        </div>
                                        <div class="img-preview-wrapper"  v-if="isImgVisible">
                                            <div class="img-overflow">
                                                <img v-bind:src="avatar" class="" alt=""/>
                                            </div>
                                            <button class="btn-close" type="button" @click="closeImgView()">
                                                <img
                                                    :src="'/images/icon-plus.svg'"
                                                    alt=""
                                                />
                                            </button>
                                        </div>
                                        <div class="error" v-if="$page.props.errors.image"
                                             v-text="$page.props.errors.image"></div>
                                        <div class="error" v-if="thumb_is_max" v-text="img_validation">
                                        </div>
                                    </div>
                                    <div class="helper-text mt-4">ファイルサイズ10MB以下、ファイル形式：jpeg, jpg, png, svg, gifのみ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-area">
                        <button class="btn btn-secondary" type="button" @click="reset">キャンセル</button>
                        <button class="btn btn-primary" type="submit">更新</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- Spinner / Loader -->
        <div class="spinner-wrapper" v-if="isLoading">
            <div class="lds-dual-ring"></div>
        </div>
        <!--  -->
    </AppLayoutUser>
</template>

<script>
import AppLayoutUser from "@/Layouts/AppLayoutUser.vue";
import {Inertia} from '@inertiajs/inertia';
import Sidebar from "../../../Layouts/User/Sidebar.vue";
import {reactive} from "vue";

export default {
    components: {AppLayoutUser, Sidebar},
    props: {
        user_data: Object,
        positions: Object,
    },
    // Modal
    data() {
        return {
            isPasswordVisble: false,
            isLoading: false,
            isImgVisible: false,
            avatar: null,
            thumb_is_max:false,
            selectedImgUrl:null,
            img_validation:null,
            form: {
                email: null,
                password: null,
                confirm_password: null,
                furigana: null,
                name: null,
                company_name: null,
                position: null,
                nickname: null,
                image: null,
                is_checked: false,
            },
        }
    },
    created() {
            this.avatar = this.user_data.avatar;
            this.form.furigana = this.user_data.furigana,
            this.form.name = this.user_data.name,
            this.form.company_name = this.user_data.company_name,
            this.form.email = this.user_data.email,
            this.form.nickname = this.user_data.nick_name
            this.form.position = this.user_data.position;
            if(this.user_data.avatar!=null){
                this.isImgVisible=true;
            }
    },
    methods: {
        showPassword() {
            this.isPasswordVisble = !this.isPasswordVisble;
        },
        reset() {
            Inertia.get(route('myPage.index'));
        },
        submit() {
            this.isLoading=true;
            if (this.$refs.avatar != null) {
                this.form.image = this.$refs.avatar.files[0];
            }
            Inertia.post(route('user.profile.submit'), this.form,{
                onFinish: () => {
                    this.isLoading = false;
                },
            });
        },
        closeImgView() {
            this.isImgVisible=false;
            this.form.image=null;
            this.$refs.avatar=null;
            this.thumb_is_max=false;
        },
        onFileChange(e) {
            this.isImgVisible=true;
            if (this.$refs.avatar.files[0].size > 10000000) {
              this.thumb_is_max=true;
                this.isImgVisible=false;
                this.img_validation='10MB以内にしてください。';
            }
            if(this.$refs.avatar.files[0]['type']!='image/jpeg' && this.$refs.avatar.files[0]['type']!='image/jpg' && this.$refs.avatar.files[0]['type']!='image/gif' && this.$refs.avatar.files[0]['type']!='image/png' &&this.$refs.avatar.files[0]['type']!='image/svg'){
                this.thumb_is_max=true;
                this.img_validation='ファイル形式をjpg,jpeg,png,gif,svgのみです。';
            }
            const file = this.$refs.avatar.files[0];
            this.avatar = URL.createObjectURL(file);
            this.form.image=this.$refs.avatar.files[0];
        },
        closeAlert(){
            this.message='!visible';
        }
    }
};
</script>
