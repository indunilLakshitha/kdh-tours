<script>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Header from "../../Layouts/Header.vue";
    import Footer from "../../Layouts/Footer.vue";
    import {Inertia} from "@inertiajs/inertia";

    export default {
        components: { Header, Footer, AppLayout },

        props: {
            errors: Object,
            positions :Object,
            license_key: String,
            token:String,
            email:String
        },
        setup(props) {
            const form = {
                email: props.email,
                name: null,
                furigana: null,
                nickname: null,
                password: null,
                confirm_password: null,
                position: null,
                birth_year: null,
                license_key: props.license_key,
                token:props.token,
            };

            function submit() {
                Inertia.post(route('invite.user-register'),form);
            }

            return { form, submit };
        },
        created() {
            const year = new Date().getFullYear();
            this.years= Array.from({ length: year - 1900 }, (value, index) => 1901 + index);
        },
    }

</script>

<template>
    <AppLayout>
        <Header />
        <div id="login-ui" class="page hr-register">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        アカウント登録
                    </div>
                    <div class="sub-heading">アカウント設定を完了させましょう。</div>
                    <div class="step-area">
                        <div class="step-single active">
                            <span class="circle"></span>
                            <span class="step-label">1.アカウント登録</span>
                        </div>
                        <div class="step-single">
                            <span class="circle"></span>
                            <span class="step-label">2.完了</span>
                        </div>
                    </div>
                    <form action="" @submit.prevent="submit" enctype="application/x-www-form-urlencoded">
                        <div class="form-group">
                            <label for="">メールアドレス</label>
                           <div>
                               <input type="text" class="form-control" v-model="form.email" readonly>
                               <div class="error" v-if="$page.props.errors.email" v-text="$page.props.errors.email" ></div>
                           </div>
                        </div>
                        <div class="form-group">
                            <label for="">名前 <span class="required">必須</span></label>
                           <div>
                               <input type="text" class="form-control" v-model="form.name">
                               <div class="error" v-if="$page.props.errors.name" v-text="$page.props.errors.name" ></div>
                           </div>
                        </div>
                        <div class="form-group">
                            <label for="">ふりがな <span class="required">必須</span></label>
                            <div>
                                <input type="text" class="form-control" v-model="form.furigana">
                                <div class="error" v-if="$page.props.errors.furigana" v-text="$page.props.errors.furigana" ></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">ニックネーム <span class="required">必須</span></label>
                            <div>
                                <input type="text" class="form-control" v-model="form.nickname">
                                <div class="error" v-if="$page.props.errors.nickname" v-text="$page.props.errors.nickname" ></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">パスワード <span class="required">必須</span></label>
                            <div>
                                <input type="password" class="form-control" v-model="form.password">
                                <div class="helper-text">文字数6～15字かつ大文字、小文字、数字、#?!@$%^&*-の組み合わせで入力してください。</div>
                                <div class="error" v-if="$page.props.errors.password" v-text="$page.props.errors.password" ></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">パスワード再入力 <span class="required">必須</span></label>
                            <div>
                                <input type="password" class="form-control"  v-model="form.confirm_password">
                                <div class="error" v-if="$page.props.errors.confirm_password" v-text="$page.props.errors.confirm_password" ></div>
                            </div>
                        </div>

                        <div class="hr-line"></div>

                        <div class="form-group">
                            <label for="">役職 <span class="required">必須</span></label>
                            <div>
                                <select class="form-select" v-model="form.position">
                                    <option v-for="(position,index) in positions"
                                            :key="index" :value="position.id">{{position.name}}</option>
                                </select>
                                <div class="error" v-if="$page.props.errors.position" v-text="$page.props.errors.position" ></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">生まれ年 <span class="required">必須</span></label>
                            <div>
                                <div>
                                    <select class="form-select w-170" v-model="form.birth_year">
                                        <option v-for="year in years" :value="year">{{ year }}</option>
                                    </select>
                                    <div class="error" v-if="$page.props.errors.birth_year"
                                         v-text="$page.props.errors.birth_year"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">ライセンスキー</label>
                            <div>
                                <input type="text" class="form-control with-border"  v-model="form.license_key" readonly>
                                <div class="error" v-if="$page.props.errors.license_key" v-text="$page.props.errors.license_key" ></div>
                            </div>
                        </div>
                        <div class="error" v-if="errors.message" v-text="errors.message" ></div>
                        <button class="btn btn-primary">アカウント登録</button>
                    </form>
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>
