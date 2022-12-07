<template>
    <AppLayoutAdmin>
        <div class="content dashboard">
            <div class="row-area">
                <div class="row-single">
                    <div class="title">ラーニングパス</div>
                    <div class="info">○件の未対応があります。</div>
                    <button class="btn btn-primary">確認する<img :src="'/images/icon-arrow-right.svg'" alt="" /></button>
                </div>
                <div class="row-single">
                    <div class="title">ライセンス管理</div>
                    <div class="info">○件の支払状況に問題があります。</div>
                    <button class="btn btn-primary">確認する<img :src="'/images/icon-arrow-right.svg'" alt="" /></button>
                </div>
                <div class="row-single">
                    <div class="title">講座管理</div>
                    <div class="info">○件の公開講座があります。</div>
                    <button class="btn btn-primary">確認する<img :src="'/images/icon-arrow-right.svg'" alt="" /></button>
                </div>
                <div class="row-single">
                    <div class="title">動画管理</div>
                    <div class="info">○件の公開動画があります。</div>
                    <button class="btn btn-primary">確認する<img :src="'/images/icon-arrow-right.svg'" alt="" /></button>
                </div>
                <div class="row-single">
                    <div class="title">ワークショップ管理</div>
                    <div class="info">○件の公開ワークショップがあります。</div>
                    <button class="btn btn-primary">確認する<img :src="'/images/icon-arrow-right.svg'" alt="" /></button>
                </div>
            </div>
        </div>
        <!-- Spinner / Loader -->
        <!-- <div class="spinner-wrapper">
            <div class="lds-dual-ring"></div>
        </div> -->
        <!--  -->
    </AppLayoutAdmin>
</template>

<script>
import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin.vue";

export default {
    components: { AppLayoutAdmin },

    setup() {
        const form = {
            email: null,
            password: null,
        };

        return { form };
    },
};
</script>
