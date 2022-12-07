<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from "../../Layouts/Header.vue";
import Footer from "../../Layouts/Footer.vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    components: { Header, Footer, AppLayout },
    props: {
        errors: Object,
        alert: String,
        isFirst:String
    },
    data() {
        return {
            isHidden: false,
        };
    },
    setup() {
        const form = {
            email: null,
            password: null,
        };

        function submit() {
            localStorage.removeItem('emails');
            Inertia.post(route('login.submit'), form);
        }

        function passwordReset(){
            Inertia.get(route('user.password.reset.view'));
        }

        return { form, submit,passwordReset };
    },
}
</script>

<template>
    <AppLayout>
        <Header />
        <div id="login-ui" class="page login">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        ログイン
                    </div>
                    <div class="sub-heading" v-if="isFirst==1">メールアドレスの登録が完了いたしました</div>
                    <div class="alert danger" v-if="$page.props.errors.message" v-text="$page.props.errors.message"></div>
                    <form action="" @submit.prevent="submit">
                        <div class="form-group">
                            <label for="">メールアドレス <span class="required">必須</span></label>
                            <div>
                                <input type="text" class="form-control" v-model="form.email">
                                <div class="error" v-if="$page.props.errors.email" v-text="$page.props.errors.email"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">パスワード <span class="required">必須</span></label>
                            <div>
                                <input type="password" class="form-control" v-model="form.password">
                                <div class="error" v-if="$page.props.errors.password" v-text="$page.props.errors.password"></div>
                            </div>
                        </div>
                        <a @click="passwordReset" class="forgot-password">パスワードを忘れた場合</a>
                        <button class="btn btn-primary">ログイン</button>
                    </form>
                </div>
            </div>
            <!-- Toast -->
            <div class="toast toast-success" v-if="$page.props.alert && isHidden">
                <div class="toast-body">{{$page.props.alert}}</div>
                <img :src="'/images/icon-toast-close.svg'" class="btn-close" @click="isHidden = true"/>
            </div>
            <!--  -->
        </div>
        <Footer />
    </AppLayout>
</template>
