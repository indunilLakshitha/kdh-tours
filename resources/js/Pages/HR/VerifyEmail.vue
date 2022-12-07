<script >
    import AppLayout from '@/Layouts/AppLayout.vue';
    import {reactive, ref} from "vue";

    export default {
        created() {
            localStorage.removeItem('emails');
        }
    }
</script>

<template>
    <AppLayout>
        <div id="login-ui" class="page notice">
            <p>
                会社アドレスにメールが届き、<br/>
                メールに記載されたアカウントURLをクリックすると <br/>
                アカウント登録をするフローに進みます。
            </p>
        </div>
    </AppLayout>
</template>
