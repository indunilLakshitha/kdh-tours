<script>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import {reactive} from "vue";
    import {Inertia} from "@inertiajs/inertia";
    import Header from "../../Layouts/Header.vue";
    import Footer from "../../Layouts/Footer.vue";

    export default {
        components: { Header, Footer, AppLayout },
        props: {
            errors:Object,
        },
        data() {
            return {
                isSubmited:false,
                form : {
                    email:'',
                }
            };
        },
        methods:{
            submit() {
                this.isSubmited=true;
                Inertia.post(route('hr.email-verification-submit'),this.form);
            }
        }
    }

</script>

<template>
    <AppLayout>
        <Header />
        <div id="login-ui" class="page email-verification">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        ご利用になる <br/> 会社メールアドレスを入力してください。
                    </div>
                    <div class="sub-heading">アカウント認証メールを送信いたします。</div>
                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <label for="">メールアドレス <span class="required">必須</span></label>
                            <div>
                                <input type="text" class="form-control" v-model="form.email">
                                <div class="error" v-if="$page.props.errors.email" v-text="$page.props.errors.email" ></div>
                            </div>
                        </div>
                        <div class="error" v-if="$page.props.errors.message" v-text="$page.props.errors.message" ></div>
                        <button class="btn btn-primary" :disabled="isSubmited">送信</button>
                    </form>
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>
