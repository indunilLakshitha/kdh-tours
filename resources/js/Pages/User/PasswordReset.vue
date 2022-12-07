<script >
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Header from "../../Layouts/Header.vue";
    import Footer from "../../Layouts/Footer.vue";
    import {Inertia} from "@inertiajs/inertia";

    export default {
        components: {Header, Footer, AppLayout},
        setup() {
            const form = {
                email: null,
            };

            function submit() {
                Inertia.post(route('user.password.reset'), form);
            }
            return {submit,form}
        }
    }
</script>

<template>
    <AppLayout>
        <Header />
        <div id="login-ui" class="page password-reset">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        パスワードの再発行
                    </div>
                    <div class="sub-heading">パスワードを再発行いたします。<br/>ご登録されたメールアドレスを入力してください。</div>
                    <div class="alert danger" v-if="$page.props.errors.message" v-text="$page.props.errors.message"></div>
                    <form action="" @submit.prevent="submit">
                        <div class="form-group">
                            <label for="">メールアドレス <span class="required">必須</span></label>
                            <div>
                                <input type="email" class="form-control" v-model="form.email">
                                <div class="error" v-if="$page.props.errors.email" v-text="$page.props.errors.email"></div>
                            </div>
                        </div>
                        <button class="btn btn-primary">送信</button>
                    </form>
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>
