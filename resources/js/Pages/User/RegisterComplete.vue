<script >
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Header from "../../Layouts/Header.vue";
    import Footer from "../../Layouts/Footer.vue";
    import {Inertia} from "@inertiajs/inertia";

    export default {
        components: { Header, Footer, AppLayout },

        props: {
            alert:String
        },
        setup(props) {
            function okay() {
                Inertia.get(route('hr.login'));
            }
            return {okay };
        },
    }

</script>

<template>
    <AppLayout>
        <Header />
        <div id="login-ui" class="page email-reg-success">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        アカウント登録の完了
                    </div>
                    <div class="sub-heading">メールアドレスの登録が完了いたしました。</div>
                    <div class="step-area">
                        <div class="step-single">
                            <span class="circle"></span>
                            <span class="step-label">1.アカウント登録</span>
                        </div>
                        <div class="step-single active">
                            <span class="circle"></span>
                            <span class="step-label">2.完了</span>
                        </div>
                    </div>
                    <form action="" @submit.prevent="okay()">
                        <button class="btn btn-primary">ログイン</button>
                    </form>
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>
