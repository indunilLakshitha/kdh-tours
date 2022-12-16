<template>
  <AppLayoutAdmin>
    <div class="content notice-create">
      <div class="page-title-area">
        <h1>HOW IT ADD</h1>
      </div>
      <div class="page-card">
        <div class="form" action="">
          <div class="form">
            <div class="form-group sml">
              <label>Title</label>

              <div class="w-100">
                <input type="text" v-model="form.title" class="form-control" />
                <!-- <div class="char-count" v-if="countText(1, 1) > 50">
                  <span>({{ countText(1, 1) }}/)</span>
                </div>
                <div class="char-count" v-if="countText(1, 1) <= 50">
                  ({{ countText(1, 1) }}/)
                </div> -->
                <div class="error" v-if="form.title.length == 0">
                  Please fill required fields
                </div>
              </div>
            </div>
            <div class="form-group sml">
              <label>Description</label>
              <div class="w-100">
                <input type="text" v-model="form.description" class="form-control" />
                <div class="error" v-if="form.description.length == 0">
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
  props: { HowItWork: Object },
  components: { AppLayoutAdmin },
  // Modal
  data() {
    return {
      open: false,
      form: useForm({
        title: this.HowItWork.title,
        description: this.HowItWork.description,
        id: this.HowItWork.id,
        status: null,
      }),
    };
  },
  methods: {
    submit(status) {
      this.form.status = status;
      if (!this.form.title.length == 0 && !this.form.description.length == 0) {
        this.form.post(route("admin.how.update"), {
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
