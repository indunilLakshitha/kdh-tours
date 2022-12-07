<template>
    <AppLayout>
        <AdminLoginHeader />
        <div id="login-ui" class="page admin-login">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">ログイン</div>
                    <div class="alert danger mt-10" v-if="errors.invalid">{{errors.invalid}}</div>
                    <form action="" @submit.prevent="submit">
                        <div class="form-group">
                            <label for=""
                                >ID
                                <span class="required">必須</span></label
                            >
                            <div>
                                <input
                                    type="text"
                                    v-model="form.email"
                                    class="form-control"
                                />
                                <!-- <div class="helper-text">注意書き</div> -->
                                <div class="error" v-if="errors.email">{{errors.email}}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for=""
                                >パスワード
                                <span class="required">必須</span></label
                            >
                            <div>
                                <input
                                    type="password"
                                    v-model="form.password"
                                    class="form-control"
                                />
                                <!-- <div class="helper-text">注意書き</div> -->
                                <div class="error" v-if="errors.password">{{errors.password}}</div>
                                <!-- <div class="error" v-if="errors.email">エラーメッセージ</div> -->
                            </div>
                        </div>
                        <!-- <a href="" class="forgot-password"
                            >パスワードを忘れた場合</a
                        > -->
                        <button class="btn btn-primary" type="test">
                            ログイン
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import AdminLoginHeader from "../../Layouts/AdminLoginHeader.vue";
import Footer from "../../Layouts/Footer.vue";

export default {
    components: { AdminLoginHeader, Footer, AppLayout },
    props: {
        errors: Object
    },
    setup() {
        const form = {
            email: null,
            password: null,
            errEmail : null,
            errPasword : null,
        };

        function submit() {
            Inertia.post("/admin/login", form);
        }

        return { form, submit };
    },
};
</script>
