<template>
  <AppLayout>
    <AdminHeader />
    <div class="layout">
      <Sidebar />
      <div class="content learning-path-create">
        <div class="page-title-area">
          <h1>ラーニングパス登録</h1>
        </div>
        <div class="page-card">
          <div class="form">
            <div class="form-group sml">
              <label>パッケージ名<!--<span>(50文字以内)</span>--></label>
              <div class="w-100">
                <input
                  type="text"
                  class="form-control"
                  v-model="form.name"
                  @input="validate()"
                />
                <div class="char-count">(0/50文字以内)</div>
                <div class="error" v-if="errors.name">
                  ワークショップ名が必須項目です。
                </div>
              </div>
            </div>
            <div class="form-group sml">
              <label>概要</label>
              <div class="w-100">
                <textarea
                    name=""
                    id=""
                    class="form-control"
                    v-model="form.summary"
                    @input="validate()"
                ></textarea>
                <div class="error" v-if="errors.summary">概要が必須項目です。</div>
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
                      validate();
                    "
                    ref="catDropdown"
                  >
                    <option
                      v-for="(cat, index) in all_learning_path_categories"
                      :key="index"
                      :value="cat.id"
                    >
                      {{ cat.name }}
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
                  カテゴリーが必須項目です。
                </div>
                <a href="" class="link">複数選択</a>
                <div class="tag-area">
                  <span
                    class="tag"
                    @click="removeCategories(cat.id)"
                    v-for="(cat, index) in category_component"
                    :key="index"
                    >{{ cat.name }}</span
                  >
                </div>
              </div>
            </div>
            <div class="form-group sml">
              <label>サムネイル<br />アップロード</label>
              <div>
                <div class="w-100" style="display: flex;">
                    <div class="file-upload-wrapper">
                        <input
                        type="file"
                        class="form-control file-upload"
                        ref="thumb"
                        @change="
                            uploadThumbnail($event);
                            validate();
                        "
                        />
                        <label for="">サムネイルをアップロード</label>
                    </div>
                    <div class="img-preview-wrapper" v-if="form.thumbnail">
                        <img :src="form.thumbnail" class="icon-draggable" alt="" />
                        <button class="btn-close" type="button" @click="removeImage()">
                        <img :src="'/images/icon-close-circle.svg'" alt="" />
                        </button>
                    </div>
                </div>
                <div>
                    <div class="error" v-if="errors.thumbnail">
                        サムネイルが必須項目です。。
                    </div>
                    <div class="error" v-if="errors.thumbnail_invalid">
                        ファイル形式をjpg,jpeg,png,gif,svgのみです。
                    </div>
                    <div class="error" v-if="errors.thumbnail_size">
                        サムネイルを5MB以内にしてください。
                    </div>
                </div>
              </div>
            </div>

            <div class="form-group align-item-fs sml">
              <label>詳細設定</label>
              <div class="flex-column">
                <div class="row-bordered">
                  <div class="label">
                    「動画」「講座」「ワークショップ」を選択してラーニングパスをつくりましょう。
                  </div>
                </div>
                <div
                  class="row-bordered"
                  v-for="(c, index) in course_components"
                  :key="index"
                >
                  <img :src="c.thumbnail" class="thumb" alt="" />
                  <div>
                    <p class="para">
                      {{ c.title }}
                    </p>
                  </div>
                  <img
                    :src="'/images/icon-close-circle.svg'"
                    class="icon-close"
                    alt=""
                    @click="removeCourseFromList(c.value)"
                  />
                </div>
                <div class="row-bordered">
                  <button class="btn btn-link" @click="course_modal = true">
                    <img :src="'/images/icon-plus-circle-sml.svg'" alt="" />講座を追加する
                  </button>
                  <!-- course add modal -->
                  <div v-if="course_modal" class="modal add-video-modal">
                    <div class="modal-content">
                      <button @click="course_modal = false" class="modal-close">
                        <img :src="'/images/icon-close-circle.svg'" alt="" />
                      </button>
                      <table class="table course-list">
                        <thead>
                          <tr>
                            <th>日付<span class="icon-sort"></span></th>
                            <th>サムネイル</th>
                            <!-- <th>カテゴリー<span class="icon-sort"></span></th> -->
                            <th>タイトル<span class="icon-sort"></span></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(course, index) in courses"
                            :key="index"
                            @click="
                              addCourseToList(course.id, course.title, course.thumbnail)
                            "
                          >
                            <td>{{ formatdate(course.created_at) }}</td>
                            <td>
                              <img :src="course.thumbnail" alt="" />
                            </td>

                            <td>
                              <div class="border-left">
                                <p>
                                  {{ course.title }}
                                </p>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--  -->
                </div>
                <div
                  class="row-bordered"
                  v-for="(c, index) in workshop_components"
                  :key="index"
                >
                  <img :src="c.thumbnail" class="thumb" alt="" />
                  <div>
                    <p class="para">
                      {{ c.title }}
                    </p>
                  </div>
                  <img
                    :src="'/images/icon-close-circle.svg'"
                    class="icon-close"
                    alt=""
                    @click="removeWorkshopFromList(c.value)"
                  />
                </div>
                <div class="row-bordered">
                  <button class="btn btn-link" @click="workshop_modal = true">
                    <img
                      :src="'/images/icon-plus-circle-sml.svg'"
                      alt=""
                    />ワークショップを追加する
                  </button>
                  <!-- workshop add modal -->
                  <div v-if="workshop_modal" class="modal add-video-modal">
                    <div class="modal-content">
                      <button @click="workshop_modal = false" class="modal-close">
                        <img :src="'/images/icon-close-circle.svg'" alt="" />
                      </button>
                      <table class="table course-list">
                        <thead>
                          <tr>
                            <th>日付<span class="icon-sort"></span></th>
                            <th>サムネイル</th>
                            <!-- <th>カテゴリー<span class="icon-sort"></span></th> -->
                            <th>タイトル<span class="icon-sort"></span></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(workshop, index) in workshops"
                            :key="index"
                            @click="
                              addWorkshopToList(
                                workshop.id,
                                workshop.name,
                                workshop.thumbnail
                              )
                            "
                          >
                            <td>{{ formatdate(workshop.created_at) }}</td>
                            <td>
                              <img :src="workshop.thumbnail" alt="" />
                            </td>

                            <td>
                              <div class="border-left">
                                <p>
                                  {{ workshop.name }}
                                </p>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--  -->
                </div>
              </div>
            </div>
            <div class="form-group align-item-fs sml">
              <label>企業名</label>
              <div class="w-100">
                <div class="check-area">
                  <input
                    type="checkbox"
                    class="form-check sml"
                    name="r1"
                    @change="changeCompanyType($event, 'ALL')"
                  /><label>ALL</label>
                  <input
                    type="checkbox"
                    class="form-check sml"
                    name="r1"
                    @change="changeCompanyType($event, 'SELECTED')"
                  /><label>会社指定</label>
                </div>
                <div v-if="form.company_type == 2">
                  <input
                    type="text"
                    class="form-control search-input"
                    placeholder="検索"
                    v-model="search_text"
                    @input="searchCompanies()"
                  />
                  <ul class="live-search-ul" v-if="(company_list.length>0)">
                    <li v-for="(company, index) in company_list" :key="index">
                        <!-- <div class="col-md-offset-1 col-md-3">
                            <img :src="customer.profile_pic" class="profile-pic" />
                        </div> -->
                        <button @click="selectCompany(company)">
                            <h4 class="name">{{ company.name }}</h4>
                        </button>
                    </li>
                  </ul>
                  <div class="tag-area">
                    <div
                        class="tag-closable"
                        v-for="(selected_company, index) in form.companies"
                        :key="index"
                    >
                        <div class="title">{{ selected_company.name }}</div>
                        <img
                        :src="'/images/icon-close-circle.svg'"
                        class="icon-close"
                        alt=""
                        @click="removeCompanyFromList(selected_company.id)"
                        />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="sub-heading">視聴後設定</div>
            <div class="form-group align-item-fs sml">
              <label>関連講座</label>
              <div class="flex-column">
                <div class="row-bordered">
                  <div class="label">
                    合わせて試聴してほしい講座を選んでください。（最大3つ）
                  </div>
                </div>
                <div class="row-bordered">
                  <img :src="'/images/course-thumb.png'" class="thumb" alt="" />
                  <div>
                    <p class="para">
                      タイトルタイトルタイトルタイトルイトルタイトルタイトルイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルイトルタイトルタイトルイトル
                    </p>
                    <small>公開日<span>2022/00/00</span></small>
                  </div>
                  <img :src="'/images/icon-close-circle.svg'" class="icon-close" alt="" />
                </div>
                <div class="row-bordered">
                  <button class="btn btn-link">
                    <img :src="'/images/icon-plus-circle-sml.svg'" alt="" />おすすめを選ぶ
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="btn-area">
          <button class="btn btn-secondary" @click="submitAndPublish(0)">
            非公開で保存
          </button>
          <button class="btn btn-primary" @click="submitAndPublish(1)">公開する</button>
        </div>
      </div>
    </div>
    <!-- Toast -->
    <div class="toast toast-danger" v-if="validateToast" sty>
      <div class="toast-body">講座が保存されました。</div>
      <img
        :src="'/images/icon-toast-close.svg'"
        class="btn-close"
        @click="this.validateToast = false"
      />
    </div>
    <!--  -->
    <AdminFooter />
  </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import AdminHeader from "../../../Layouts/AdminHeader.vue";
import Sidebar from "../../../Layouts/Sidebar.vue";
import AdminFooter from "../../../Layouts/AdminFooter.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import moment from "moment";

export default {
  props: {
    courses: Object,
    workshops: Object,
    categories: Object,
    companies: Object,
  },
  components: { AdminHeader, AppLayout, Sidebar, AdminFooter },
  data() {
    return {
      open: false,
      validateToast: false,
      search_text: "",
      // customers:
      form: useForm({
        name: "",
        summary: "",
        status: "",
        thumbnail: "",
        courses: [],
        workshops: [],
        company_type: "",
        companies: [],
        categories: [],
      }),
      all_learning_path_categories: this.categories,
      errors: {
        name: false,
        summary: false,
        thumbnail: false,
        courses: false,
        workshops: false,
        company_type: false,
        companies: false,
        categories: false,
      },
      category_component: [],
      course_components: [],
      workshop_components: [],
      validated: false,
      course_modal: false,
      workshop_modal: false,
      company_list: false,
      company_list: [],
    };
  },
  methods: {
    searchCompanies() {
      let search_text = this.search_text;
      let company_list_new = [];
      this.companies.filter(function (item) {
        if (item["name"].match(search_text)) {
          console.log(item["name"]);
          company_list_new.push(item);
        }
      });
      if (company_list_new.length > 0 && search_text.length > 0) {
        this.company_list = company_list_new;
      } else {
        this.company_list = [];
      }
    },
    selectCompany(company) {
      this.form.companies.push(company);
    },
    removeCompanyFromList(id) {
      this.form.companies = this.form.companies.filter((e) => e.id !== id);
    },
    submitAndPublish(status) {
      this.validate();
      this.validated = true;
      this.form.status = status;
      if (this.validate()) {
        this.form.post(route("admin.learning-path.store"), {
          onSuccess: () => {
            console.log("success");
          },
          onFinish: () => {
            console.log("failed");
          },
        });
      }else{
        this.validateToast= true;
        setTimeout(() => (this.validateToast = false), 3000);
      }
    },
    uploadThumbnail(event) {
      if (this.validateFile(this.$refs.thumb.files[0].name)) {
        this.thumb_loading = true;
        let user_token = document
          .querySelector('meta[name="csrf-token"]')
          .getAttribute("content");
        let formData = new FormData();

        formData.append("thumbnail", event.target.files[0]);
        axios
          .post("/admin/learning-path/thumb", formData, {
            headers: {
              _token: user_token,
              "Content-Type": "multipart/form-data",
            },
          })
          .then((response) => {
            if (response.data.success == true) {
              this.form.thumbnail = response.data.url;
              this.thumb_loading = false;
              this.validate();
            } else {
              this.thumb_loading = false;
              console.log("failed");
            }
          });
      }
    },
    removeImage() {
      this.form.thumbnail = "";
      this.$refs.thumb.value = null;
    },
    addCourseToList(course_id, title, thumnail) {
      this.course_components.push({
        id: this.course_component_id++,
        type: "vedio_select",
        thumbnail: thumnail,
        value: course_id,
        title: title,
      });

      this.form.courses.push(course_id);

      this.course_component_id++;

      this.course_modal = false;
    },
    removeCourseFromList(id) {
      this.course_components = this.course_components.filter((e) => e.value !== id);
      this.form.courses = this.form.courses.filter((e) => e !== id);
    },
    removeWorkshopFromList(id) {
      this.workshop_components = this.workshop_components.filter((e) => e.value !== id);
      this.form.workshops = this.form.workshops.filter((e) => e !== id);
    },
    addWorkshopToList(course_id, name, thumnail) {
      this.workshop_components.push({
        id: this.workshop_component_id++,
        type: "vedio_select",
        thumbnail: thumnail,
        value: course_id,
        name: name,
      });

      this.form.workshops.push(course_id);

      this.workshop_component_id++;

      this.workshop_modal = false;
    },
    validate(model = null) {
      let required = 0;
      if (!model) {
        if (this.validated == true) {
          if (!this.form.name.length > 0) {
            this.errors.name = true;
            required = 1;
          } else {
            this.errors.name = false;
          }
          if (!this.form.summary.length > 0) {
            this.errors.summary = true;
            required = 1;
          } else {
            this.errors.summary = false;
          }
          if (!this.form.thumbnail.length > 0) {
            this.errors.thumbnail = true;
            required = 1;
          } else {
            this.errors.thumbnail = false;
          }
          if (!this.form.categories.length > 0) {
            this.errors.categories = true;
            required = 1;
          } else {
            this.errors.categories = false;
          }
        }
      }
      if (required == 1) {
        return false;
      } else {
        return true;
      }
    },
    formatdate(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      return formattedDate; //20171019
    },
    addNewCategory() {
      let user_token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

      axios
        .post("/admin/learning-path/category", {
          category_name: this.new_category,
          headers: {
            _token: user_token,
          },
        })
        .then((response) => {
          this.all_learning_path_categories = response.data[0];
          this.open = false;
          this.new_category = "";
        });
    },
    addCategories(event) {
      if (
        event.target.options[event.target.options.selectedIndex].text ===
        "カテゴリーを選択"
      ) {
        return;
      }

      let is_added = this.category_component.filter(
        (e) => e.name === event.target.options[event.target.options.selectedIndex].text
      );
      if (!is_added.length > 0) {
        this.category_component.push({
          id: this.category_component_id++,
          name: event.target.options[event.target.options.selectedIndex].text,
          value: event.target.options[event.target.options.selectedIndex].value,
        });
        this.form.categories.push(event.target.value);
        this.category_component_id++;
      }
    },
    removeCategories(id) {
      this.category_component = this.category_component.filter((e) => e.id !== id);
      this.form.categories = [];
      this.category_component.forEach((element) => {
        this.form.categories.push(element.value);
      });
      this.setDefaultSelected = true;
    },
    validateFile(name) {
      var allowedExtension = ["jpeg", "jpg", "svg", "gif", "jfif", "png"];
      var fileExtension = name.split(".").pop().toLowerCase();
      var isValidFile = false;

      for (var index in allowedExtension) {
        if (fileExtension === allowedExtension[index]) {
          isValidFile = true;

          break;
        }
      }

      if (this.$refs.thumb.files[0].size > 5000000) {
        isValidFile = true;
        this.errors.thumbnail_size = true;
      } else {
        this.errors.thumbnail_size = false;
      }
      if (isValidFile) {
        this.errors.thumbnail_invalid = false;
      } else {
        this.form.thumbnail = "";
        this.errors.thumbnail_invalid = true;
      }

      console.log(isValidFile);
      return isValidFile;
    },
    changeCompanyType(event, type) {
      if (event.target.checked && type == "ALL") {
        this.form.company_type = 1;
        this.company_list = false;
      } else if (event.target.checked && type == "SELECTED") {
        this.form.company_type = 2;
        this.company_list = true;
      } else if (!event.target.checked && type == "ALL") {
        this.form.company_type = "";
        this.company_list = false;
      } else if (!event.target.checked && type == "SELECTED") {
        this.form.company_type = "";
        this.company_list = false;
      }
    },
  },
};
</script>
