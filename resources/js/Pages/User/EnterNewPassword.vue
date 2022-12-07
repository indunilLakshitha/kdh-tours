<script >
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Header from "../../Layouts/Header.vue";
    import Footer from "../../Layouts/Footer.vue";
    import {Inertia} from "@inertiajs/inertia";
    export default {
        components: {Header, Footer, AppLayout},
        props:{
          email:String,
          token:String
        },
        setup(props) {
            const form = {
                password: null,
                confirm_password: null,
                email:props.email,
                token:props.token
            };

            function submit() {
                Inertia.post(route('user.password.change'), form);
            }
            return {submit,form}
        }
    }
</script>

<template>
    <AppLayout>
        <Header />
        <div id="login-ui" class="page new-password">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        パスワードの再設定
                    </div>
                    <div class="sub-heading">新しいパスワードを入力し、更新を押してください</div>
                    <div class="alert danger" v-if="$page.props.errors.message" v-text="$page.props.errors.message"></div>
                    <form action="" @submit.prevent="submit">
                        <div class="form-group">
                            <label for="">新しいパスワード <span class="required">必須</span></label>
                            <div>
                                <input type="password" class="form-control" v-model="form.password">
                                <div class="helper-text">文字数6～15字かつ大文字、小文字、数字、#?!@$%^&*-の組み合わせで入力してください。</div>
                                <div class="error" v-if="$page.props.errors.password" v-text="$page.props.errors.password"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="flex-wrap">新しいパスワードを再入力 <span class="required">必須</span></label>
                            <div>
                                <input type="password" class="form-control" v-model="form.confirm_password">
                                <div class="error" v-if="$page.props.errors.confirm_password" v-text="$page.props.errors.confirm_password"></div>
                            </div>
                        </div>
                        <button class="btn btn-primary">更新</button>
                    </form>
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>
