<template>
  <AppLayoutUser>
    <div class="user-layout">
      <div class="content full-width">
        <div id="login-ui" class="page email-reg-success">
          <div class="card">
            <div class="card-content">
              <div class="card-title">注文確定</div>
              <div class="sub-heading">ライセンスキーの登録が完了いたしました。</div>
              <div class="step-area">
                <div class="step-single">
                  <span class="circle"></span>
                  <span class="step-label">1.アカウント登録</span>
                </div>
                <div class="step-single active">
                  <span class="circle"></span>
                  <span class="step-label">2.完了</span>
                </div>
              </div>
              <form action="" @submit.prevent="submit">
                <button class="btn btn-primary w-260" @click="submit">
                  マイアカウント
                </button>
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
  components: { AppLayoutUser, Sidebar },
  props: {
    errors: Object,
    current_user: String,
    email_list: Object,
  },
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
    localStorage.removeItem("emails");
  },
  methods: {
    submit() {
      Inertia.get(route("myPage.index"));

    },
    back() {
      localStorage.removeItem("emails");
      Inertia.get(route("license.index"), this.form);
    },
  },
};
</script>
