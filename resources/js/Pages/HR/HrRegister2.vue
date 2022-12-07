<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import Header from "../../Layouts/Header.vue";
import Footer from "../../Layouts/Footer.vue";

export default {
    components: { Header, Footer, AppLayout },
    props: {
        user_id: String,
        email: String,
        positions: Object,
        occupations: Object,
        industries: Object,
        prefectures: Object,
    },
    data() {
        return {
            form: {
                company_name: '',
                industry: '',
                occupation: '',
                position: '',
                birth_year: '',
                post_code: '',
                prefecture: '',
                address_1: '',
                address_2: '',
                building_name: '',
                telephone_no: '',
                user_id: this.user_id,
                email: this.email,
                checked: false
            },
            years:[]
        };
    },
    created() {
            const year = new Date().getFullYear();
            this.years= Array.from({ length: year - 1900 }, (value, index) => 1901 + index);
    },
    methods: {
        submit() {
            Inertia.post(route('hr.register2-submit'), this.form);
        },
    },
    watch:{

    }
}
</script>

<template>
    <AppLayout>
        <Header />
        <div id="login-ui" class="page hr-register">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        アカウント登録
                    </div>
                    <div class="sub-heading">会社のアカウント設定を完了させましょう。</div>
                    <div class="step-area">
                        <div class="step-single active">
                            <span class="circle"></span>
                            <span class="step-label">1.アカウント登録</span>
                        </div>
                        <div class="step-single">
                            <span class="circle"></span>
                            <span class="step-label">2.完了</span>
                        </div>
                    </div>
                    <form @submit.prevent="submit" enctype="application/x-www-form-urlencoded">
                        <div class="form-group">
                            <label for="">会社名 <span class="required">必須</span></label>
                            <div>
                                <input type="text" class="form-control" v-model="form.company_name">
                                <div class="error" v-if="$page.props.errors.company_name"
                                    v-text="$page.props.errors.company_name"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">業種 <span class="required">必須</span></label>
                            <div>
                                <select class="form-select" v-model="form.industry">
                                    <option v-for="(industry,index) in industries" :key="index" :value="industry.id">
                                        {{industry.name}}</option>
                                </select>
                                <div class="error" v-if="$page.props.errors.industry"
                                    v-text="$page.props.errors.industry"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">職種 <span class="required">必須</span></label>
                            <div>
                                <select class="form-select" v-model="form.occupation">
                                    <option v-for="(occupation,index) in occupations" :key="index"
                                        :value="occupation.id">{{occupation.name}}</option>
                                </select>
                                <div class="error" v-if="$page.props.errors.occupation"
                                    v-text="$page.props.errors.occupation"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">役職 <span class="required">必須</span></label>
                            <div>
                                <select class="form-select" v-model="form.position">
                                    <option v-for="(position,index) in positions" :key="index" :value="position.id">
                                        {{position.name}}</option>
                                </select>
                                <div class="error" v-if="$page.props.errors.position"
                                    v-text="$page.props.errors.position"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">生まれ年 <span class="required">必須</span></label>
                            <div>
                                <select class="form-select w-170" v-model="form.birth_year">
                                    <option v-for="year in years" :value="year">{{ year }}</option>
                                </select>
                                <div class="error" v-if="$page.props.errors.birth_year"
                                    v-text="$page.props.errors.birth_year"></div>
                            </div>
                        </div>

                        <div class="hr-line"></div>

                        <div class="sub-heading">会社情報を入力してください。</div>
                        <div class="form-group">
                            <label for="">郵便番号</label>
                            <div>
                                <input type="tel" class="form-control" min="0" placeholder="6001234"
                                    v-model="form.post_code">
                                <div class="helper-text">郵便場番号はハイフン抜きで入力してください。</div>
                                <div class="error" v-if="$page.props.errors.post_code"
                                    v-text="$page.props.errors.post_code" @keypress="isNumber($event)"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">都道府県</label>
                            <div>
                                <select class="form-select w-170" v-model="form.prefecture">
                                    <option v-for="(prefecture,index) in prefectures" :key="index"
                                        :value="prefecture.id">{{prefecture.name}}</option>
                                </select>
                                <div class="error" v-if="$page.props.errors.prefecture"
                                    v-text="$page.props.errors.prefecture"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">住所1</label>
                            <div>
                                <input type="text" class="form-control" v-model="form.address_1">
                                <div class="error" v-if="$page.props.errors.address_1"
                                    v-text="$page.props.errors.address_1"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">住所2</label>
                            <div>
                                <input type="text" class="form-control" v-model="form.address_2">
                                <div class="error" v-if="$page.props.errors.address_2"
                                    v-text="$page.props.errors.address_2"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">建物名・号室</label>
                            <div>
                                <input type="text" class="form-control" v-model="form.building_name">
                                <div class="error" v-if="$page.props.errors.building_name"
                                    v-text="$page.props.errors.building_name"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">電話番号</label>
                            <div>
                                <input type="tel" maxlength="10" min="1" class="form-control" v-model="form.telephone_no">
                                <div class="error" v-if="$page.props.errors.telephone_no"
                                    v-text="$page.props.errors.telephone_no"></div>
                            </div>
                        </div>
                        <div class="check-area">
                            <input type="checkbox" class="form-check" v-model="form.checked" id="agree">
                            <label for="agree"><a href="">利用規約</a> <a href="">プライバシーポリシー</a>に同意します</label>
                            <div class="error" v-if="$page.props.errors.checked" v-text="$page.props.errors.checked">
                            </div>
                        </div>
                        <button class="btn btn-primary" :disabled="!form.checked"
                            :class="{ 'btn-secondary': !form.checked}">アカウント登録</button>
                    </form>
                </div>
            </div>
        </div>
        <Footer />
    </AppLayout>
</template>
