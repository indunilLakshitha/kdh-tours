<template>
  <AppLayout>
    <AdminHeader />
    <div class="layout">
      <Sidebar />
      <div class="content learning-path-create">
        <div class="page-title-area">
          <h1>Top Section Details</h1>
        </div>
        <div class="page-card">
          <div class="form">
            
            <div class="form-group sml">
              <label>Description 1</label>
              <div class="w-100">
                <textarea
                    name=""
                    id=""
                    class="form-control"
                    v-model="form.description_1"
                    @input="validate()"
                ></textarea>
                <div class="error" v-if="errors.summary">概要が必須項目です。</div>
                </div>
            </div>
            
            <div class="form-group sml">
              <label>Description 2</label>
              <div class="w-100">
                <textarea
                    name=""
                    id=""
                    class="form-control"
                    v-model="form.description_2"
                    @input="validate()"
                ></textarea>
                <div class="error" v-if="errors.summary">概要が必須項目です。</div>
                </div>
            </div>
            <div class="form-group sml">
              <label>Type 1</label>
              <div class="w-100">
                <input type="text"  v-model="form.type_1" class="form-control" />
                <!-- <div class="error" v-if="form.answer.length == 0">
                  Please fill required fields
                </div> -->
              </div>
            </div>
            <div class="form-group sml">
              <label>Type 2</label>
              <div class="w-100">
                <input type="text" v-model="form.type_2" class="form-control" />
                <!-- <div class="error" v-if="form.answer.length == 0">
                  Please fill required fields
                </div> -->
              </div>
            </div>
            <div class="form-group sml">
              <label>Type 3</label>
              <div class="w-100">
                <input type="text" v-model="form.type_3"  class="form-control" />
                <!-- <div class="error" v-if="form.answer.length == 0">
                  Please fill required fields
                </div> -->
              </div>
            </div>
            <div class="form-group sml">
              <label>Image</label>
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
  
  },
  components: { AdminHeader, AppLayout, Sidebar, AdminFooter },
  data() {
    return {
      open: false,
      validateToast: false,
      search_text: "",
      // customers:
      form: useForm({
        description_1: "",
        description_2: "",
        type_1: "",
        type_2: "",
        type_3: "",
        status: "",
        thumbnail: "",
        image_id: "",
   
      }),
      errors: {
        name: false,
        summary: false,
        thumbnail: false,
        courses: false,
        workshops: false,
        company_type: false,
        companies: false,
        categories: false,
        thumbnail_size: false,
        thumbnail_invalid: false,
      },

    };
  },
  methods: {
   
    submitAndPublish(status) {
      this.validate();
      this.validated = true;
      this.form.status = status;
      if (true) {
      // if (this.validate()) {
        this.form.post(route("admin.top.store"), {
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

        formData.append("image", event.target.files[0]);
        axios
          .post("/admin/image", formData, {
            headers: {
              _token: user_token,
              "Content-Type": "multipart/form-data",
            },
          })
          .then((response) => {
            // if (response.data.success == true) {
              this.form.thumbnail = response.data.url;
              this.form.image_id = response.data.image_id;
              this.thumb_loading = false;
              this.validate();
            // } else {
              // this.thumb_loading = false;
              // console.log("failed");
            // }
          });
      }
    },
    removeImage() {
      this.form.thumbnail = "";
      this.$refs.thumb.value = null;
    },
    
    validate(model = null) {
      
        return true;
      
    },
    formatdate(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      return formattedDate; //20171019
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
    
  },
};
</script>
