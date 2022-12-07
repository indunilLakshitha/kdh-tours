<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Header from "../../Layouts/Header.vue";
import Footer from "../../Layouts/Footer.vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    components: {Header, Footer, AppLayout},
}
</script>

<template>
    <AppLayout>
        <Header/>
        <div id="login-ui" class="email-reg-success">
            <div class="card">
                <div class="card-content">
                    <div class="sub-heading">メールアドレスの登録が完了いたしました</div>
                    <form action="">
                        <button class="btn btn-primary">OK</button>
                    </form>
                </div>
            </div>
        </div>
        <Footer/>
    </AppLayout>
</template>
