<script>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Header from "../../Layouts/Header.vue";
    import Footer from "../../Layouts/Footer.vue";
    import {Inertia} from "@inertiajs/inertia";

    export default {
        components: { Header, Footer, AppLayout },
        props: {
            errors: Object,
            alert: String
        },
        data() {
            return {
                isHidden: false,
            };
        },
        setup() {

        },
    }
</script>

<template>
    <AppLayout>
        <Header />
        <div id="login-ui" class="page user-login">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        ログイン
                    </div>
                    <form action="">
                        <div class="form-group">
                            <label for="">メールアドレス <span class="required">必須</span></label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">パスワード <span class="required">必須</span></label>
                            <input type="text" class="form-control">
                        </div>
                        <a href="" class="forgot-password">パスワードを忘れた場合</a>
                        <button class="btn btn-primary">ログイン</button>
                    </form>
                </div>
            </div>
            <!-- Toast -->
            <div class="toast toast-success" v-if="!props.alert">
                <div class="toast-body">{{$page.props.alert}}</div>
                <img :src="'/images/icon-toast-close.svg'" class="btn-close" @click="isHidden = true"/>
            </div>
            <!--  -->
        </div>
        <Footer />
    </AppLayout>
</template>
