<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Inertia} from "@inertiajs/inertia";
import Footer from "@/Layouts/Footer.vue";
import Header from "@/Layouts/Header.vue";
import {reactive, ref} from "vue";

export default {
    components: {Header, Footer, AppLayout},
    props: {
        errors: Object,
        current_user: String,
        email_list: Object
    },
    data() {
        return {
            form: {
                user: this.current_user,
                emails: [{}],
                inputCount: 0,
            },
            isValid:true

        };
    },
    created() {
        this.form.emails = JSON.parse(localStorage.getItem("emails") || '[]')
    },
    mounted() {
        console.log(this.errors);
        console.log(`${this.errors['emails.0']}`);
    },
    methods: {
        submit() {
            localStorage.removeItem('emails');
            localStorage.setItem("emails", JSON.stringify(this.form.emails));
            Inertia.get(route('hr.confirm.emails'), this.form);
            this.isValid=true;
        },

        // function removeEmailsFormList(index) {
        //     localStorage.removeItem('emails');
        //     this.emails.splice(this.emails.indexOf(index), 1);
        //     localStorage.setItem("emails", JSON.stringify(this.emails));
        //     this.emails = JSON.parse(localStorage.getItem("emails") || '[]')
        //     form.email_list = null
        //     form.email_list = JSON.parse(localStorage.getItem("emails") || '[]')
        // }

        addInputFeidToForm() {
            this.isValid=false;
            this.form.emails.push({value: '',key:this.form.inputCount++});
        },

        removeEmailInput(index) {
            this.isValid=false;
            this.form.emails.splice(index, 1);
            localStorage.setItem("emails", JSON.stringify(this.emails));
        }

    },
}

</script>

<template>
    <AppLayout>
        <Header/>
        <div id="login-ui" class="page email-reg">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        利用者のメールアドレス登録
                    </div>
                    <div class="sub-heading">ご利用されるメールアドレスを登録しましょう。</div>
                    <div class="step-area">
                        <div class="step-single active">
                            <span class="circle"></span>
                            <span class="step-label">1.メールアドレス登録</span>
                        </div>
                        <div class="step-single">
                            <span class="circle"></span>
                            <span class="step-label">2.完了</span>
                        </div>
                    </div>
                    <form @submit.prevent="submit">
                        <div class="form-group" readonly>
                            <label for="">メールアドレス</label>
                            <input type="text" class="form-control as-label" v-model="$props.current_user" readonly>
                        </div>

                        <div class="form-group" v-for="(email,index) in form.emails" :key="index">
                            <label for="">メールアドレス{{ index + 1 }}</label>
                            <div>
                                <div class="input-flex">
                                    <input type="email" class="form-control" v-model="email.value">
                                    <button class="btn btn-link email-delete" type="button" title="Delete"
                                            @click="this.removeEmailInput(index)">
                                        <img :src="'/images/icon-email-delete.svg'" alt=""/>
                                    </button>
                                </div>
                                <div class="error" v-if="$page.props.errors['emails.'+index]!=null && isValid"
                                     v-text="`${$page.props.errors['emails.'+index]}`"></div>
                            </div>
                        </div>
                        <button class="btn btn-link" type="button" @click="this.addInputFeidToForm()">
                            <img :src="'/images/icon-plus.svg'" alt="">アドレスを追加する
                        </button>
                        <p>メールアドレスを登録し終わったら確認を押してください。</p>
                        <button type="submit" class="btn btn-primary">確認</button>
                    </form>
                </div>
            </div>
        </div>
        <Footer/>
    </AppLayout>
</template>
