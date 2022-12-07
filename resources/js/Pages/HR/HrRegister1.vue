<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Header from "../../Layouts/Header.vue";
import Footer from "../../Layouts/Footer.vue";

export default {
    components: { Header, Footer, AppLayout },
    props: {
        email: String,
    },
    data() {
        return {

        };
    },
    setup(props) {
        const form = {
            name: '',
            furigana: '',
            nick_name: '',
            password: '',
            email: props.email,
            confirm_password: ''
        };

        function submit() {
            Inertia.post(route('hr.register1-submit'), form);
        }
        return { form, submit };
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
                    <div class="sub-heading">会社のアカウント設定を完了させましょう。</div>
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
                    <form @submit.prevent="submit" enctype="application/x-www-form-urlencoded">

                        <input type="hidden" class="form-control" v-model="form.email">
                        <div class="form-group">
                            <label for="">名前 <span class="required">必須</span></label>
                            <div>
                                <input type="text" class="form-control" v-model="form.name">
                                <div class="error" v-if="$page.props.errors.name" v-text="$page.props.errors.name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">ふりがな <span class="required">必須</span></label>
                            <div>
                                <input type="text" class="form-control" v-model="form.furigana">
                                <div class="error" v-if="$page.props.errors.furigana"
                                    v-text="$page.props.errors.furigana"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">ニックネーム <span class="required">必須</span></label>
                            <div>
                                <input type="text" class="form-control" v-model="form.nick_name">
                                <div class="error" v-if="$page.props.errors.nick_name"
                                    v-text="$page.props.errors.nick_name"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">パスワード <span class="required">必須</span></label>
                            <div>
                                <input type="password" class="form-control" v-model="form.password">
                                <div class="helper-text">文字数6～15字かつ大文字、小文字、数字、#?!@$%^&*-の組み合わせで入力してください。</div>
                                <div class="error" v-if="$page.props.errors.password"
                                    v-text="$page.props.errors.password"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">パスワード再入力 <span class="required">必須</span></label>
                            <div>
                                <input type="password" class="form-control" v-model="form.confirm_password">
                                <div class="error" v-if="$page.props.errors.confirm_password"
                                    v-text="$page.props.errors.confirm_password"></div>
                            </div>
                        </div>
                        <button class="btn btn-secondary">次へ</button>
                    </form>
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>
