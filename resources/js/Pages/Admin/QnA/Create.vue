<template>
  <AppLayoutAdmin>
    <div class="content notice-create">
      <div class="page-title-area">
        <h1>Q n A ADD</h1>
      </div>
      <div class="page-card">
        <div class="form" action="">
          <div class="form">
            <div class="form-group sml">
              <label>Question</label>

              <div class="w-100">
                <input type="text" v-model="form.question" class="form-control" />
                <!-- <div class="char-count" v-if="countText(1, 1) > 50">
                  <span>({{ countText(1, 1) }}/)</span>
                </div>
                <div class="char-count" v-if="countText(1, 1) <= 50">
                  ({{ countText(1, 1) }}/)
                </div> -->
                <div class="error" v-if="form.question.length == 0">
                  Please fill required fields
                </div>
              </div>
            </div>
            <div class="form-group sml">
              <label>Answer</label>
              <div class="w-100">
                <input type="text" v-model="form.answer" class="form-control" />
                <div class="error" v-if="form.answer.length == 0">
                  Please fill required fields
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="btn-area">
        <button class="btn btn-secondary" @click="submit(0)">TEMPORY</button>
        <button class="btn btn-primary" @click="submit(1)">SUBMIT</button>
      </div>
    </div>
    <!-- Toast required-->
    <div class="toast toast-danger" v-if="requiredToast" sty>
      <div class="toast-body">Please fill required fields</div>
      <img
        :src="'/images/icon-toast-close.svg'"
        class="btn-close"
        @click="this.requiredToast = false"
      />
    </div>
    <!--  -->
  </AppLayoutAdmin>
</template>

<script>
import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin.vue";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
  props: { notice_categories: Object },
  components: { AppLayoutAdmin },
  // Modal
  data() {
    return {
      open: false,
      form: useForm({
        question: "",
        answer: "",
        status: null,
      }),
    };
  },
  methods: {
    submit(status) {
      this.form.status = status;
      if (!this.form.question.length == 0 && !this.form.answer.length == 0) {
        this.form.post(route("admin.qna.store"), {
          onSuccess: () => {},
          onFinish: () => {},
        });
      } else {
        this.requiredToast = true;
        console.log(this.requiredToast);
      }
    },
    countText(type, modl) {
      let max = 0;
      let now = 0;

      if (type == 1) {
        max = 50; //for title
        now = this.form.title.length;
      } else if (type == 2) {
        max = 50; //for title
      }

      return now;
    },
  },
};
</script>
