<template>
  <AppLayoutAdmin>
    <div class="content notice-create">
      <div class="page-title-area">
        <h1>お知らせの投稿</h1>
      </div>
      <div class="page-card">
        <div class="form" action="">
          <div class="form">
            <div class="form-group sml">
              <label>タイトル</label>

              <div class="w-100">
                <input
                  type="text"
                  v-model="form.title"
                  class="form-control"
                  @input="validate(1)"
                />
                <div class="char-count" v-if="countText(1, 1) > 50">
                  <span>({{ countText(1, 1) }}/50文字以内)</span>
                </div>
                <div class="char-count" v-if="countText(1, 1) <= 50">
                  ({{ countText(1, 1) }}/50文字以内)
                </div>
                <div class="error" v-if="form.title.length == 0 && errors.title">
                  タイトルは必須項目です。
                </div>
              </div>
            </div>
            <div class="form-group sml">
              <label>リンク</label>
              <div class="w-100">
                <input
                  type="text"
                  v-model="form.link"
                  class="form-control"
                  @input="validate(0)"
                />
                <div class="error" v-if="form.link.length > 0 && errors.link">
                  無効なリンク
                </div>
              </div>
            </div>
            <div class="form-group align-item-fs sml">
              <label>カテゴリー</label>
              <div class="flex-column">
                <div class="flex-row">
                  <select
                    name=""
                    id=""
                    class="form-select w-420"
                    @change="
                      addCategories($event);
                      validate(2);
                    "
                    ref="catDropDown"
                  >
                    <option value="" disabled :selected="setDefaultSelected">
                      カテゴリーを選択
                    </option>
                    <option
                      v-for="(category, index) in all_notice_categories"
                      :key="index"
                      :value="category.id"
                    >
                      {{ category.category }}
                    </option>
                    <option v-if="!all_notice_categories" value="">
                      No Categories at this moment
                    </option>
                  </select>

                  <button @click="open = true" class="btn btn-link">
                    <img :src="'/images/icon-plus-circle-sml.svg'" alt="" />
                  </button>

                  <!-- Modal -->
                  <div v-if="open" class="modal">
                    <div class="modal-content">
                      <button @click="open = false" class="modal-close">
                        <img :src="'/images/icon-close-circle.svg'" alt="" />
                      </button>
                      <label>カテゴリー</label>
                      <input
                        type="text"
                        class="form-control"
                        v-model="new_category"
                        @keypress.enter="addNewCategory"
                      />
                      <button class="btn btn-primary" @click="addNewCategory">
                        追加
                      </button>
                    </div>
                  </div>
                </div>
                <div class="error" v-if="errors.categories">
                  カテゴリーは必須項目です。
                </div>
                <div class="tag-area">
                  <span
                    class="tag"
                    @click="removeCategories(cat.cat_id)"
                    v-for="(cat, index) in category_component"
                    :key="index"
                    >{{ cat.name }}</span
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="btn-area">
        <button class="btn btn-secondary" @click="submit(1)">非公開で保存</button>
        <button class="btn btn-primary" @click="submit(2)">公開する</button>
      </div>
    </div>
    <!-- Toast required-->
    <div class="toast toast-danger" v-if="requiredToast" sty>
      <div class="toast-body">すべての必須項目を入力してください。</div>
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
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
  props: { notice_categories: Object },
  components: { AppLayoutAdmin },
  // Modal
  data() {
    return {
      open: false,
      setDefaultSelected: true,
      category_component: [],
      all_notice_categories: this.notice_categories,
      new_category: null,
      category_component_id: 1,
      requiredToast: false,
      errors: {
        title: false,
        link: false,
        categories: false,
      },
      form: useForm({
        title: "",
        link: "",
        categories: [],
        status: null,
      }),
    };
  },
  methods: {
    addCategories(event) {
      console.log(event);
      if (
        event.target.options[event.target.options.selectedIndex].text ===
        "カテゴリーを選択"
      ) {
        return;
      }
      this.category_component = [];
      this.form.categories = [];
      let is_added = this.category_component.filter(
        (e) => e.name === event.target.options[event.target.options.selectedIndex].text
      );
      if (!is_added.length > 0) {
        this.category_component.push({
          id: this.category_component_id++,
          cat_id: event.target.value,
          name: event.target.options[event.target.options.selectedIndex].text,
          value: null,
        });
        this.form.categories.push(event.target.value);
        this.category_component_id++;
      }

      this.$refs.catDropDown.value = "";
    },
    removeCategories(id) {
      console.log(this.form.categories);
      console.log(id);
      // this.category_component = this.category_component.filter((e) => e.cat_id !== id);
      // this.form.categories = this.form.categories.filter((e) => e != id);
      this.category_component = [];
      this.form.categories = [];
      this.setDefaultSelected = true;
      console.log(this.form.categories);
    },
    addNewCategory() {
      let user_token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

      axios
        .post("/admin/notice/category", {
          category_name: this.new_category,
          headers: {
            _token: user_token,
          },
        })
        .then((response) => {
          this.all_notice_categories = response.data[0];
          this.new_category = null;
          this.open = false;
        });
    },
    submit(status) {
      this.form.status = status;
      this.validate(3);
      if (!this.errors.categories && !this.errors.title && !this.errors.link) {
        this.form.post(route("admin.notice.store"), {
          onSuccess: () => {},
          onFinish: () => {},
        });
      } else {
        this.requiredToast = true;
        console.log(this.requiredToast);
      }
    },
    validate(type) {
      // console.log(this.category_component.length);
      if (this.form.link.length > 0) {
        var pattern = new RegExp(
          "^(https?:\\/\\/)?" + // protocol
            "((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|" + // domain name
            "((\\d{1,3}\\.){3}\\d{1,3}))" + // OR ip (v4) address
            "(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*" + // port and path
            "(\\?[;&a-z\\d%_.~+=-]*)?" + // query string
            "(\\#[-a-z\\d_]*)?$",
          "i"
        ); // fragment locator
        if (!!pattern.test(this.form.link)) {
          this.errors.link = false;
        } else {
          this.errors.link = true;
        }
      }
      if (type == 1) {
        if (this.form.title.length == 0) {
          this.errors.title = true;
        } else {
          this.errors.title = false;
        }
      }
      if (type == 2) {
        if (this.category_component.length == 0) {
          this.errors.categories = true;
        } else {
          this.errors.categories = false;
        }
      }
      if (type == 3) {
        if (this.category_component.length == 0) {
          this.errors.categories = true;
        } else {
          this.errors.categories = false;
        }
        if (this.form.title.length == 0) {
          this.errors.title = true;
        } else {
          this.errors.title = false;
        }
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
