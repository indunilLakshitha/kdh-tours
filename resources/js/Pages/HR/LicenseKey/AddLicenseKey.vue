<template>
  <AppLayoutUser>
    <div class="user-layout">
      <div class="content full-width">
        <div id="login-ui" class="page email-reg">
          <div class="card">
            <div class="card-content">
              <div class="card-title">ライセンスキーの追加</div>
              <div class="sub-heading">
                利用される社員のメールアドレスを追加登録しましょう。
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
              <form @submit.prevent="submit">
                <div
                  class="form-group"
                  v-for="(email, index) in form.emails"
                  :key="index"
                >
                  <label for="">メールアドレス{{ index + 1 }}</label>
                  <div>
                    <div class="input-flex">
                      <input type="email" class="form-control" v-model="email.value" />
                      <button
                        class="btn btn-link email-delete"
                        type="button"
                        title="Delete"
                        @click="this.removeEmailInput(index)"
                      >
                        <img :src="'/images/icon-email-delete.svg'" alt="" />
                      </button>
                    </div>
                    <div
                      class="error"
                      v-if="$page.props.errors['emails.' + index] != null && isValid"
                      v-text="`${$page.props.errors['emails.' + index]}`"
                    ></div>
                  </div>
                </div>
                <button
                  class="btn btn-link"
                  type="button"
                  @click="this.addInputFeidToForm()"
                >
                  <img :src="'/images/icon-plus.svg'" alt="" />アドレスを追加する
                </button>
                <p>メールアドレスを登録し終わったら確認を押してください。</p>
                <button type="submit" class="btn btn-primary" disabled v-if="this.form.emails.length == 0">確認</button>
                <button type="submit" class="btn btn-primary" v-if="this.form.emails.length > 0" >確認</button>
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

export default {
  components: { AppLayoutUser, Sidebar },
  props: {
    errors: Object,
    current_user: String,
  },
  data() {
    return {
      form: {
        user: this.current_user,
        emails: [{}],
        inputCount: 0,
      },
      isValid: true,
    };
  },
  created() {
    this.form.emails = JSON.parse(localStorage.getItem("emails") || "[]");
  },
  mounted() {
    console.log(this.errors);
    console.log(`${this.errors["emails.0"]}`);
  },
  methods: {
    submit() {
      if(this.form.emails.length > 0){

      localStorage.removeItem("emails");
      localStorage.setItem("emails", JSON.stringify(this.form.emails));
      Inertia.get(route("license.confirm"), this.form);
      this.isValid = true;
      }else{

      }
    },

    addInputFeidToForm() {
      this.isValid = false;
      this.form.emails.push({ value: "", key: this.form.inputCount++ });
    },

    removeEmailInput(index) {
      this.isValid = false;
      this.form.emails.splice(index, 1);
      localStorage.setItem("emails", JSON.stringify(this.emails));
    },
  },
};
</script>
