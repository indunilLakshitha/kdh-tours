<script >
    import AppLayout from '@/Layouts/AppLayout.vue';
    import {reactive, ref} from "vue";
    import {Inertia} from "@inertiajs/inertia";

    export default {
        created() {
            localStorage.removeItem('emails');
        },
        methods: {
            submit() {
                Inertia.get(route('user.home'));
            },
        },
    }
</script>

<template>
    <AppLayout>
        <div id="login-ui" class="page notice">
            <p>
                【管理者→利用者視点に移動します】<br/>
                会社アドレスにメールが届き、<br/>
                登録URLとライセンスキーが発行されています。<br/>
                登録URLをクリックして、アカウント登録に進みます。
            </p>
            <button class="btn btn-primary" @click="submit">OK</button>
        </div>
    </AppLayout>
</template>
