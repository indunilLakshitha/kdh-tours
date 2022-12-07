<template>
  <AppLayoutUser>
    <div class="user-layout">
      <div class="content full-width">
        <div id="login-ui" class="page email-reg-confirm">
          <div class="card">
            <div class="card-content">
              <div class="card-title">ライセンスキーの確認</div>
              <div class="sub-heading">
                以下のメールアドレスをライセンスキーとして登録しますがよろしいですか？
              </div>
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
              <div class="hr-line"></div>
              <form action="" @submit.prevent="submit">
                <div
                  class="form-group align-center"
                  v-for="(email, index) in this.email_list"
                  :key="index"
                >
                  <label for="">メールアドレス{{ index + 1 }}</label>
                  <input
                    type="text"
                    class="form-control as-label"
                    v-model="email_list[index]"
                    :key="index"
                    readonly
                  />
                  <div class="error" :v-if="errors">{{ errors.emails }}</div>
                </div>
                <div class="hr-line"></div>
                <a class="btn btn-link"  @click="this.back()">
                  <img :src="'/images/icon-back.svg'" alt="" />修正する
                </a>
                <div class="error text-center" :v-if="errors.message">{{ errors.message }}</div>
                <p>登録したメールアドレスに招待を送信します。</p>
                <div class="box-border">
                  <div class="red-text">
                    ※メールアドレスに間違いがないか今一度ご確認ください。
                  </div>
                  <p>
                    <u>メールアドレス10件分のご登録</u
                    >で<span>「コンシェルジュ特典」</span>を <br />
                    無償でお受けいただけます。
                  </p>
                </div>
                <button class="btn btn-primary">注文確定して送信</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayoutUser>
</template>

<script>
import AppLayoutUser from "@/Layouts/AppLayoutUser.vue";
import { Inertia } from "@inertiajs/inertia";
import Sidebar from "../../../Layouts/User/Sidebar.vue";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
  props: {
    errors: Object,
    current_user: String,
    email_list: Object,
  },
  components: { AppLayoutUser, Sidebar },
  // Modal
  data() {
    return {
      emails: [],
      form: useForm({
        emails: this.email_list,
        current_user: this.current_user,
      }),
    };
  },
  created() {
    this.emails = this.email_list;
  },
  methods: {
    submit() {
      this.form.post(route("license.send"), {
        onSuccess: () => {
          this.emails = null;
        },
      });
    },
    back() {
      Inertia.get(route("license.index"), this.form);
    },
  },
};
</script>
