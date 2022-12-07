<script >
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Header from "../../Layouts/Header.vue";
    import Footer from "../../Layouts/Footer.vue";
    import {Inertia} from "@inertiajs/inertia";
    export default {
        components: { Header, Footer, AppLayout },
        props: {
            errors: Object
        },
    }
</script>

<template>
    <AppLayout>
        <Header />
        <div id="login-ui" class="page password-reset-complete">
            <div class="card">
                <div class="card-content">
                    <div class="card-title" v-if="$page.props.errors.message" v-text="$page.props.errors.message"></div>
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>
