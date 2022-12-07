<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Header from "../../Layouts/Header.vue";
    import Footer from "../../Layouts/Footer.vue";
</script>

<template>
    <AppLayout>
        <Header />
        <div id="login-ui" class="page password-reset-complete">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        パスワードが再設定されました
                    </div>
                    <form action="">
                        <button class="btn btn-primary">ログイン</button>
                    </form>
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>
