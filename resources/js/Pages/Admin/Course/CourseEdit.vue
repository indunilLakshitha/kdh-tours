<template>
  <AppLayoutAdmin>
    <div class="content course-register">
      <div class="page-title-area">
        <h1>講座登録</h1>
      </div>
      <div class="page-card">
        <div class="form" action="">
          <div class="form">
            <div class="form-group sml">
              <label>講座名 </label>
              <div class="w-100">
                <input
                  type="text"
                  class="form-control"
                  v-model="form.name"
                  placeholder="講座名 (50文字以内)"
                />
                <div class="error" v-if="errors.name && form.name.length == 0">
                  {{ errors.name }}
                </div>
                <div class="error" v-if="fe_erors.title && form.name.length == 0">
                  講座名が必須項目です。
                </div>
                <div class="char-count" v-if="countText(1, 1) > 50">
                  <span>({{ countText(1, 1) }}/50文字以内)</span>
                </div>
                <div class="char-count" v-if="countText(1, 1) <= 50">
                  ({{ countText(1, 1) }}/50文字以内)
                </div>
                <!-- <div class="char-count">{{ countText(1) }}文字以内</div -->
              </div>
            </div>
            <div class="form-group sml">
              <label>講座概要</label>
              <div class="w-100">
                <textarea
                  placeholder="講座概要"
                  name=""
                  id=""
                  class="form-control"
                  v-model="form.summery"
                ></textarea>
                <div class="error" v-if="errors.summery && form.summery.length == 0">
                  {{ errors.summery }}
                </div>
                <div class="error" v-if="fe_erors.summery && form.summery.length == 0">
                  講座概要が必須項目です。
                </div>
              </div>
            </div>
            <div class="form-group align-item-fs sml">
              <label>カテゴリー</label>
              <div class="flex-column">
                <div class="flex-row">
                  <select
                    class="form-select w-420"
                    @change="addCategories($event)"
                    ref="catDropDown"
                  >
                    <option value="" disabled :selected="setDefaultSelected">
                      カテゴリーを選択
                    </option>
                    <option
                      v-for="(category, index) in all_course_categories"
                      :key="index"
                      :value="category.id"
                    >
                      {{ category.name }}
                    </option>
                    <option v-if="!all_course_categories" value="">
                      No Categories at this moment
                    </option>
                  </select>
                  <button @click="open = true" type="button" class="btn btn-link">
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
                      <button
                        @click="addNewCategory"
                        type="button"
                        class="btn btn-primary"
                      >
                        追加
                      </button>
                    </div>
                  </div>
                </div>
                <div
                  class="error"
                  v-if="errors.categories && form.categories.length == 0"
                >
                  {{ errors.categories }}
                </div>
                <div
                  class="error"
                  v-if="fe_erors.categories && form.categories.length == 0"
                >
                  カテゴリーが必須項目です。
                </div>
                <a class="link">複数選択</a>
                <!-- <p class="helper-text">複数選択</p> -->
                <div class="tag-area">
                  <span
                    class="tag"
                    @click="removeCategories(cat.id, cat.cat_id)"
                    v-for="(cat, index) in category_component"
                    :key="index"
                    >{{ cat.name }}</span
                  >
                </div>
              </div>
            </div>
            <div class="form-group sml">
              <label class="mt-40">キーワード</label>
              <div class="flex-column">
                <input
                  placeholder="キーワード"
                  name=""
                  id=""
                  v-model="keyword"
                  type="text"
                  maxlength="40"
                  class="form-control w-420"
                  @keyup.enter="addKeyword()"
                />
                <p class="helper-text">※エンターキーを押すと複数入れることが可能です。</p>
                <div class="error" v-if="errors.keywords && form.keywords.length == 0">
                  {{ errors.keywords }}
                </div>
                <div class="error" v-if="fe_erors.keywords && form.keywords.length == 0">
                  講座概要が必須項目です。
                </div>
              </div>
            </div>
            <div class="form-group align-item-fs sml">
              <label>&nbsp;</label>
              <div class="tag-area">
                <span
                  @click="removeKeyword(item)"
                  class="tag"
                  v-for="(item, index) in form.keywords"
                  :key="index"
                  >{{ item }}</span
                >
              </div>
            </div>
            <div class="form-group sml">
              <label
                >サムネイル<br />アップロード
                <div class="char-count">(5MB以内)</div></label
              >
              <div>
                <div class="d-flex">
                  <div class="file-upload-wrapper">
                    <input
                      type="file"
                      ref="thumb"
                      @change="thumbUploadStatus($event)"
                      class="form-control file-upload"
                    />
                    <label for="">サムネイルをアップロード</label>
                  </div>
                  <div class="img-preview-wrapper" v-if="!thumb_upload">
                    <img :src="thumb_link" class="icon-draggable" alt="" />
                    <button class="btn-close" type="button" @click="removeImage()">
                      <img :src="'/images/icon-close-circle.svg'" alt="" />
                    </button>
                  </div>
                </div>
                <div class="error" v-if="errors.thumbnail && thumb_er && !thumb_is_max">
                  {{ errors.thumbnail }}
                </div>
                <div class="error" v-if="thumb_er && thumb_is_max">
                  {{ thumbnail_error }}
                </div>
                <div class="error" v-if="thumb_er && thumb_invalid_type">
                  {{ thumbnail_error_type }}
                </div>
                <div class="error" v-if="fe_erors.thumbnail && thumb_er">
                  サムネイルは必須項目です。
                </div>
              </div>
            </div>
            <div class="form-group align-item-fs sml">
              <label>詳細設定</label>
              <div class="flex-column">
                <div class="row-bordered">
                  <div class="label">
                    講座のチャプター順に「大項目タイトル」と「動画」を選択してパッケージをつくりましょう。
                  </div>
                </div>
                <div v-for="(row, index) in form.vedio_components" :key="index">
                  <div class="row-bordered" v-if="row.type === 'vedio_select'">
                    <img
                      :src="'/images/icon-draggable.svg'"
                      class="icon-draggable"
                      alt=""
                    />
                    <img
                      :src="form.vedio_components[index].thumbnail"
                      class="thumb"
                      alt=""
                    />
                    <p class="para">
                      {{ row.title }}
                    </p>
                    <img
                      @click="reoveFromVedioComponents(row.id)"
                      :src="'/images/icon-close-circle.svg'"
                      class="icon-close"
                      alt=""
                    />
                  </div>
                  <div class="row-bordered" v-if="row.type === 'title' && form.vedio_components[index].value">
                    <img
                      :src="'/images/icon-draggable.svg'"
                      class="icon-draggable"
                      alt=""
                    />
                    <div>
                      <textarea
                        required
                        name=""
                        id=""
                        v-model="form.vedio_components[index].value"
                        class="form-control w-680 h-64"
                        placeholder="大項目タイトル (40文字以内)"
                      ></textarea>
                      <div class="error" v-if="!form.vedio_components[index].value">
                        大項目タイトルが必須です。
                      </div>
                      <div
                        class="char-count"
                        v-if="form.vedio_components[index].value.length > 40"
                      >
                        <span
                          >({{
                            form.vedio_components[index].value.length
                          }}/40文字以内)</span
                        >
                      </div>
                      <div
                        class="char-count"
                        v-if="form.vedio_components[index].value.length <= 40"
                      >
                        ({{ form.vedio_components[index].value.length }}/40文字以内)
                      </div>
                    </div>
                    <!-- <p class="char-count">(<span>30</span>文字以内)</p> -->
                    <img
                      @click="reoveFromVedioComponents(row.id)"
                      :src="'/images/icon-close-circle.svg'"
                      class="icon-close"
                      alt=""
                    />
                  </div>
                </div>
                <div class="row-bordered">
                  <button type="button" class="btn btn-link" @click="addMajorTitle">
                    <img
                      :src="'/images/icon-plus-circle-sml.svg'"
                      alt=""
                    />大項目タイトルを追加する
                  </button>
                </div>
                <div class="row-bordered">
                  <button type="button" class="btn btn-link" @click="open2 = true">
                    <img
                      :src="'/images/icon-plus-circle-sml.svg'"
                      alt=""
                    />動画・講座を追加する
                  </button>
                  <!-- Video add modal -->
                  <div v-if="open2" class="modal add-video-modal">
                    <div class="modal-content">
                      <button @click="open2 = false" class="modal-close">
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
                            v-for="(vedio, index) in videos"
                            :key="index"
                            @click="
                              addVedioToList(vedio.id, vedio.title, vedio.thumbnail)
                            "
                          >
                            <td>{{ formatdate(vedio.created_at) }}</td>
                            <td>
                              <img :src="vedio.thumbnail" alt="" />
                            </td>
                            <td>
                              {{ vedio.title }}
                            </td>
                            <!-- <td>
                              <div class="border-left">
                                <p>
                                  {{ vedio.summary }}
                                </p>
                              </div>
                            </td> -->
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--  -->
                </div>
              </div>
            </div>
            <div class="sub-heading">レポート設定</div>
            <div class="form-group align-item-fs sml">
              <label>レポート項目</label>
              <div class="flex-column">
                <div class="row-bordered">
                  <div class="label">
                    レポート項目を追加してレポートページを作成しましょう。順番を変えたりカスタマイズ可能です。
                  </div>
                </div>
                <div v-for="(report, index) in form.reports" :key="index">
                  <div class="row-bordered" v-if="report.type === 'text'">
                    <img
                      :src="'/images/icon-draggable.svg'"
                      class="icon-draggable"
                      alt=""
                    />
                    <div class="w-100">
                      <textarea
                        name=""
                        id=""
                        v-model="form.reports[index].title"
                        class="form-control h-64"
                        placeholder="テキストエリアのタイトル (40文字以内)"
                      ></textarea>
                      <div class="error" v-if="!form.reports[index].title">
                        タイトルが必須項目です。
                      </div>
                      <div
                        class="char-count"
                        v-if="form.reports[index].title.length > 40"
                      >
                        <span>({{ form.reports[index].title.length }}/40文字以内)</span>
                      </div>
                      <div
                        class="char-count"
                        v-if="form.reports[index].title.length <= 40"
                      >
                        ({{ form.reports[index].title.length }}/40文字以内)
                      </div>
                      <!-- <div class="char-count">(<span>40</span>文字以内)</div> -->
                      <textarea
                        name=""
                        ref="reports"
                        id=""
                        v-model="form.reports[index].description"
                        class="form-control h-158"
                        placeholder="テキストエリアの説明文 "
                      ></textarea>
                      <div class="error" v-if="!form.reports[index].description">
                        テキストエリアの説明文が必須です。
                      </div>
                    </div>
                    <img
                      @click="removeReportDocumentArea(report.id, index)"
                      :src="'/images/icon-close-circle.svg'"
                      class="icon-close"
                      alt=""
                    />
                  </div>
                  <div class="row-bordered" v-if="report.type === 'doc'">
                    <img
                      :src="'/images/icon-draggable.svg'"
                      class="icon-draggable"
                      alt=""
                    />
                    <div class="w-100">
                      <div class="upload-flex">
                        <textarea
                          name=""
                          id=""
                          v-model="form.reports[index].title"
                          class="form-control h-64"
                          placeholder="資料ダウンロードのタイトル (40文字以内)"
                        ></textarea>
                        <div class="file-upload-wrapper">
                          <input
                            type="file"
                            @change="fileLable($event, report.id, 'sheet')"
                            class="form-control file-upload file"
                            ref="reports"
                          />
                          <label for="" v-if="!form.reports[index].file_name"
                            >ファイルをアップロード</label
                          >
                          <label for="" v-if="form.reports[index].file_name">{{
                            trimFileName(form.reports[index].file_name)
                          }}</label>
                          <div
                            class="spinner-wrapper"
                            v-if="form.reports[index].upload_start == 1"
                          >
                            <div class="lds-dual-ring"></div>
                          </div>
                        </div>
                      </div>
                      <div
                        class="error"
                        v-if="
                          !form.reports[index].title || !form.reports[index].file_name
                        "
                      >
                        タイトルがとファイル必須です。
                      </div>
                      <div
                        class="char-count"
                        v-if="form.reports[index].title.length > 40"
                      >
                        <span>({{ form.reports[index].title.length }}/40文字以内)</span>
                      </div>
                      <div
                        class="char-count"
                        v-if="form.reports[index].title.length <= 40"
                      >
                        ({{ form.reports[index].title.length }}/40文字以内)
                      </div>
                      <!-- <div class="char-count">(<span>40</span>文字以内)</div> -->
                      <textarea
                        name=""
                        id=""
                        class="form-control h-158"
                        placeholder="資料ダウンロードの説明文"
                        v-model="form.reports[index].description"
                      ></textarea>
                      <div class="error" v-if="!form.reports[index].description">
                        資料ダウンロードの説明文が必須です。
                      </div>
                    </div>
                    <img
                      @click="removeReportDocumentArea(report.id, index)"
                      :src="'/images/icon-close-circle.svg'"
                      class="icon-close"
                      alt=""
                    />
                  </div>
                </div>
                <div class="row-bordered">
                  <button type="button" class="btn btn-link" @click="addReportTextArea">
                    <img
                      :src="'/images/icon-plus-circle-sml.svg'"
                      alt=""
                    />テキストエリアを追加する
                  </button>
                </div>
                <div class="row-bordered">
                  <button
                    type="button"
                    class="btn btn-link"
                    @click="addReportDocumentArea"
                  >
                    <img
                      :src="'/images/icon-plus-circle-sml.svg'"
                      alt=""
                    />資料ダウンロードを追加する
                  </button>
                </div>
              </div>
            </div>
            <div class="sub-heading">視聴後設定</div>
            <!-- <div> -->
            <div v-for="(uploadArea, index) in form.viewings" :key="index">
              <div class="form-group sml mb-0" v-if="uploadArea.type === 'doc'">
                <label>資料アップロード</label>
                <div class="flex-column">
                  <div class="row-bordered">
                    <img
                      :src="'/images/icon-draggable.svg'"
                      class="icon-draggable"
                      alt=""
                    />
                    <div class="w-100">
                      <div class="upload-flex">
                        <textarea
                          name=""
                          id=""
                          v-model="form.viewings[index].title"
                          class="form-control h-64"
                          placeholder="タイトル (40文字以内)"
                        ></textarea>
                        <div class="file-upload-wrapper">
                          <input
                            type="file"
                            @change="fileLable($event, uploadArea.id, 'viewings')"
                            ref="viewing_docs"
                            class="form-control file-upload file"
                          />
                          <label for="" v-if="!form.viewings[index].file_name"
                            >ファイルをアップロード</label
                          >
                          <label for="" v-if="form.viewings[index].file_name">
                            {{ trimFileName(form.viewings[index].file_name) }}
                          </label>
                          <div
                            class="spinner-wrapper"
                            v-if="form.viewings[index].upload_start == 1"
                          >
                            <div class="lds-dual-ring"></div>
                          </div>
                        </div>
                      </div>
                      <div
                        class="error"
                        v-if="
                          !form.viewings[index].title || !form.viewings[index].file_name
                        "
                      >
                        タイトルとファイルが必須項目です。
                      </div>
                      <div
                        class="char-count"
                        v-if="form.viewings[index].title.length > 40"
                      >
                        <span>({{ form.viewings[index].title.length }}/40文字以内)</span>
                      </div>
                      <div
                        class="char-count"
                        v-if="form.viewings[index].title.length <= 40"
                      >
                        ({{ form.viewings[index].title.length }}/40文字以内)
                      </div>
                      <!-- <div class="char-count">(<span>40</span>文字以内)</div> -->
                    </div>
                    <img
                      @click="removeDocumentUploadArea(uploadArea.id, index)"
                      :src="'/images/icon-close-circle.svg'"
                      class="icon-close"
                      alt=""
                    />
                  </div>
                </div>
              </div>
              <div class="form-group sml" v-if="uploadArea.type === 'sheet'">
                <label>ワークシート<br />アップロード</label>
                <div class="flex-column">
                  <div class="row-bordered">
                    <img
                      :src="'/images/icon-draggable.svg'"
                      class="icon-draggable"
                      alt=""
                    />
                    <div class="w-100">
                      <div class="upload-flex">
                        <textarea
                          name=""
                          id=""
                          v-model="form.viewings[index].title"
                          class="form-control h-64"
                          placeholder="タイトル  (40文字以内)"
                        ></textarea>
                        <div class="file-upload-wrapper">
                          <input
                            type="file"
                            ref="viewing_docs"
                            @change="fileLable($event, uploadArea.id, 'viewings')"
                            class="form-control file-upload file"
                          />
                          <label for="" v-if="!form.viewings[index].file_name"
                            >ファイルをアップロード</label
                          >
                          <label for="" v-if="form.viewings[index].file_name">{{
                            trimFileName(form.viewings[index].file_name)
                          }}</label>
                          <div
                            class="spinner-wrapper"
                            v-if="form.viewings[index].upload_start == 1"
                          >
                            <div class="lds-dual-ring"></div>
                          </div>
                        </div>
                      </div>
                      <div
                        class="error"
                        v-if="
                          !form.viewings[index].title && !form.viewings[index].file_name
                        "
                      >
                        タイトルとファイルが必須項目です。
                      </div>
                      <div
                        class="char-count"
                        v-if="form.viewings[index].title.length > 40"
                      >
                        <span>({{ form.viewings[index].title.length }}/40文字以内)</span>
                      </div>
                      <div
                        class="char-count"
                        v-if="form.viewings[index].title.length <= 40"
                      >
                        ({{ form.viewings[index].title.length }}/40文字以内)
                      </div>
                      <!-- <div class="char-count">(<span>40</span>文字以内)</div> -->
                    </div>
                    <img
                      @click="removeDocumentUploadArea(uploadArea.id, index)"
                      :src="'/images/icon-close-circle.svg'"
                      class="icon-close"
                      alt=""
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group sml mb-0">
              <label for="">&nbsp;</label>
              <div class="flex-column">
                <div class="row-bordered">
                  <button
                    class="btn btn-link"
                    type="button"
                    @click="addDocumentUploadArea"
                  >
                    <img :src="'/images/icon-plus-circle-sml.svg'" alt="" />資料を追加する
                  </button>
                </div>
                <div class="row-bordered">
                  <button
                    class="btn btn-link"
                    type="button"
                    @click="addWorksheetUploadArea"
                  >
                    <img
                      :src="'/images/icon-plus-circle-sml.svg'"
                      alt=""
                    />ワークシートを追加する
                  </button>
                </div>
              </div>
            </div>
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
      </div>
      <div class="btn-area">
        <button
          class="btn btn-secondary"
          :disabled="is_submit_enabled"
          @click="submitCoursePrivately"
        >
          非公開で保存
        </button>
        <button
          class="btn btn-primary"
          :disabled="is_submit_enabled"
          @click="submitCoursePublish"
        >
          公開する
        </button>
      </div>
    </div>
    <!-- Toast -->
    <div class="toast toast-success" v-if="successToast" sty>
      <div class="toast-body">講座が保存されました。</div>
      <img
        :src="'/images/icon-toast-close.svg'"
        class="btn-close"
        @click="this.successToast = false"
      />
    </div>
    <!--  -->
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

    <!-- Spinner / Loader -->
    <div class="spinner-wrapper" v-if="isLoading">
      <div class="lds-dual-ring"></div>
    </div>
    <!--  -->
  </AppLayoutAdmin>
</template>

<script>
import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin.vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import { useForm } from "@inertiajs/inertia-vue3";
import moment from "moment";

export default {
  components: { AppLayoutAdmin, moment },
  props: {
    course_categories: Object,
    errors: Object,
    videos: Object,
    course: Object,
  },
  // Modal
  data() {
    return {
      open: false,
      open2: false,
      form: useForm({
        course_id: "",
        name: "",
        summery: "",
        thumbnail: null,
        categories: [],
        keywords: [],
        advanced: [],
        status: 1, // 1 = publish 0 = not active
        vedio_components: [
          // {id:1,type:'vedio_preview',value:''},
          // {id:2,type:'title',value:''},
          // {id:3,type:'vedio_select',value:''},
        ],
        reports: [],
        viewings: [],
        old_thumb: 0,
      }),
      image_form: {
        image: null,
      },
      vedio_components_id: 1,
      viewings_id: 1,
      reports_id: 1,
      category_component: [],
      category_component_id: 1,
      keyword_component: [],
      keyword_component_id: 1,
      report_name: null,
      new_category: null,
      all_course_categories: this.course_categories,
      thumb_upload: true,
      thumb_link: "",
      keyword: "",
      successToast: false,
      requiredToast: false,
      is_submit_enabled: false,
      thumb_er: null,
      thumbnail_error: "サムネイルを5MB以内にしてください。",
      thumb_is_max: false,
      thumbnail_error_type: "ファイル形式をjpg,jpeg,png,gif,svgのみです。",
      thumb_invalid_type: false,
      isLoading: false,
      setDefaultSelected: true,
      fe_erors: {
        title: false,
        summery: false,
        thumbnail: false,
        categories: false,
        keywords: false,
      },
    };
  },
  created() {
    this.form.name = this.course.title;
    this.form.course_id = this.course.id;
    this.form.summery = this.course.summery;
    this.thumb_link = this.course.thumbnail;
    // this.form.thumbnail = ' ';
    this.thumb_upload = false;
    this.form.old_thumb = 1;

    // loading categories
    this.course.course_has_categories.forEach((e, index) => {
      this.category_component.push({
        id: this.category_component_id++,
        name: e.category.name,
        value: null,
        cat_id: e.category.id,
      });
      this.form.categories.push(e.category.id);
      this.category_component_id++;
    });
    // loading keywords
    this.course.keywords.forEach((e, index) => {
      this.form.keywords.push(e.keyword);
      this.keyword = "";
    });

    // load major title and vedios
    this.course.vedios_and_titles.forEach((e, index) => {
      if (e.type == 0) {
        this.form.vedio_components.push({
          id: this.vedio_components_id++,
          type: "title",
          value: e.content,
        });
      } else {
        this.form.vedio_components.push({
          id: this.vedio_components_id++,
          type: "vedio_select",
          value: e.vedio_id,
          thumbnail: this.getVedioDetails(e.vedio_id, 1),
          title: this.getVedioDetails(e.vedio_id, 12),
        });
      }

      this.form.vedio_components_id++;
    });

    // load post viewings
    this.course.post_viewings.forEach((e) => {
      if (e.type == 1) {
        this.form.viewings.push({
          id: this.viewings_id++,
          type: "doc",
          title: e.title,
          document: e.document,
          old_file: 1,
          file_name: e.document.split(this.course.id + "/")[1],
        });
      } else {
        this.form.viewings.push({
          id: this.viewings_id++,
          type: "sheet",
          title: e.title,
          old_file: 1,
          document: e.document,
          file_name: e.document.split(this.course.id + "/")[1],
        });
      }
    });

    // load report area
    this.course.reports.forEach((e) => {
      if (e.type == 0) {
        this.form.reports.push({
          id: this.reports_id++,
          type: "text",
          title: e.title,
          old_file: 1,
          status: 1,
          description: e.description,
          document: null,
          file_name: null,
        });
      } else {
        this.form.reports.push({
          id: this.reports_id++,
          type: "doc",
          title: e.title,
          status: 1,
          old_file: 1,
          description: e.description,
          document: e.document,
          file_name: e.document.split(this.course.id + "/")[1],
        });
      }
    });
  },
  methods: {
    getVedioDetails(vedio_id, type) {
      var vedio_data;
      this.videos.forEach((e) => {
        if (e.id == vedio_id) {
          vedio_data = e;
        }
      });

      if (type == 1) {
        return vedio_data.thumbnail;
      } else {
        return vedio_data.title;
      }
    },
    addMajorTitle() {
      console.log('call');
      this.form.vedio_components.push({
        id: this.vedio_components_id++,
        type: "title",
        value: " ",
      });
      this.form.vedio_components_id++;
    },
    removeMajorTitle() {
      this.form.vedio_components.push({
        id: this.vedio_components_id++,
        type: "title",
        value: "",
      });
      a.splice(
        a.findIndex((e) => e.name === "tc_001"),
        1
      );
      this.form.vedio_components_id++;
    },
    addVedioSelect() {
      this.form.vedio_components.push({
        id: this.vedio_components_id++,
        type: "vedio_select",
        value: "",
      });
      this.vedio_components_id++;
      this.open2 = true;
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
          value: null,
          cat_id: event.target.value,
        });
        this.form.categories.push(event.target.value);
        this.category_component_id++;
      }

      this.$refs.catDropDown.value = "";
    },
    removeCategories(id, cat_id) {
      this.category_component = this.category_component.filter((e) => e.id !== id);
      this.form.categories = this.form.categories.filter((e) => e !== cat_id);
      this.setDefaultSelected = true;
    },
    addKeyword() {
      if (!this.keyword == "") {
        let is_added = this.form.keywords.filter((e) => e === this.keyword);
        if (!is_added.length > 0) {
          // if(this.keyword.length >= 40){

          // }else{

          this.form.keywords.push(this.keyword);
          // }
        }
      }
      this.keyword = "";
    },
    removeKeyword(text) {
      this.form.keywords = this.form.keywords.filter((e) => e !== text);
    },
    addReportTextArea() {
      this.form.reports.push({
        id: this.reports_id++,
        type: "text",
        title: "",
        description: null,
        old_file: 0,
        document: null,
        upload_start: 0,
        file_name: null,
      });
    },
    addReportDocumentArea() {
      this.form.reports.push({
        id: this.reports_id++,
        type: "doc",
        title: "",
        description: null,
        old_file: 0,
        upload_start: 0,
        document: null,
        file_name: null,
      });
    },
    addDocumentUploadArea() {
      this.form.viewings.push({
        id: this.viewings_id++,
        type: "doc",
        title: "",
        old_file: 0,
        upload_start: 0,
        document: null,
        file_name: null,
      });
    },
    addWorksheetUploadArea() {
      this.form.viewings.push({
        id: this.viewings_id++,
        type: "sheet",
        title: "",
        document: null,
        upload_start: 0,
        old_file: 0,
        file_name: null,
      });
    },
    thumbUploadStatus(event) {
      if (this.$refs.thumb.files[0].size > 5000000) {
        this.thumb_upload = true;
        this.thumb_is_max = true;
        this.$refs.thumb.value = null;
        this.thumb_link = "";
        this.thumb_er = 1;
        this.form.thumbnail = null;
        return;
      } else if (!this.validateFile(this.$refs.thumb.files[0].name)) {
        this.thumb_upload = true;
        this.thumb_invalid_type = true;
        this.$refs.thumb.value = null;
        this.thumb_link = "";
        this.thumb_er = 1;
        this.form.thumbnail = null;
        return;
      } else {
        this.thumb_is_max = false;
        this.thumb_er = null;
        this.thumb_upload = false;
        let input = this.$refs.thumb;
        let file = input.files;
        if (file && file[0]) {
          let reader = new FileReader();
          reader.onload = (e) => {
            this.thumb_link = e.target.result;
          };
          reader.readAsDataURL(file[0]);
        }

        this.form.thumbnail = this.$refs.thumb.files[0];
        this.$emit("input", file[0]);
        this.form.old_thumb = 0;
      }
    },
    validateFile(name) {
      var allowedExtension = ["jpeg", "jpg", "svg", "gif", "jfif","png"];
      var fileExtension = name.split(".").pop().toLowerCase();
      var isValidFile = false;

      for (var index in allowedExtension) {
        if (fileExtension === allowedExtension[index]) {
          isValidFile = true;
          break;
        }
      }
      return isValidFile;
    },
    fileLable(event, id, type) {
      if (type == "viewings") {
        this.form.viewings.forEach((e) => {
          if (e.id == id) {
            e.upload_start = 1;
          }
        });
      } else {
        this.form.reports.forEach((e) => {
          if (e.id == id) {
            e.upload_start = 1;
          }
        });
      }
      let user_token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
      let formData = new FormData();

      formData.append("document", event.target.files[0]);
      axios
        .post("/admin/course/document", formData, {
          headers: {
            _token: user_token,
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (type == "viewings") {
            this.form.viewings.forEach((e) => {
              if (e.id == id) {
                e.file_name = response.data.url.split("temp/")[1];
                e.document = response.data.url;
                e.upload_start = 0;
              }
            });
          } else if (type == "sheet") {
            this.form.reports.forEach((e) => {
              if (e.id == id) {
                e.file_name = response.data.url.split("temp/")[1];
                e.document = response.data.url;
                e.upload_start = 0;
              }
            });
          }
        });
    },
    trimFileName(filename) {
      var name = filename.substr(0, filename.lastIndexOf("."));
      var ext = filename.substr(filename.lastIndexOf(".") + 1);
      var file_name_to_show;
      if (name.length > 15) {
        file_name_to_show = name.slice(0, 15) + "...." + ext;
      } else {
        file_name_to_show = name + "." + ext;
      }
      return file_name_to_show;
    },
    removeImage() {
      this.thumb_upload = true;
      this.$refs.thumb.value = null;
      this.thumb_link = "";
      this.form.old_thumb = 0;
    },
    submitCoursePrivately() {
      if (!this.validate()) {
        return;
      }
      this.isLoading = true;
      this.thumb_er = true;
      this.is_submit_enabled = true;
      this.form.status = 0;
      if (this.old_thumb == 0) {
        if (this.$refs.thumb && this.thumb_upload !== true) {
          this.form.thumbnail = this.$refs.thumb.files[0];
        } else {
          this.form.thumbnail = null;
        }
      }

      this.form.post(route("course.update"), {
        onSuccess: () => {
          this.clearAll();
          this.successToast = true;
          this.is_submit_enabled = false;
          this.isLoading = false;
        },
        onFinish: () => {
          this.is_submit_enabled = false;
          this.isLoading = false;
        },
      });
    },
    submitCoursePublish() {
      if (!this.validate()) {
        return;
      }
      this.isLoading = true;
      this.thumb_er = true;
      this.is_submit_enabled = true;
      this.form.status = 1;
      if (this.old_thumb == 0) {
        if (this.$refs.thumb && this.thumb_upload !== true) {
          this.form.thumbnail = this.$refs.thumb.files[0];
        } else {
          this.form.thumbnail = null;
        }
      }

      this.form.post(route("course.update"), {
        onSuccess: () => {
          this.clearAll();
          this.successToast = true;
          this.is_submit_enabled = false;
          this.isLoading = false;
          setTimeout(() => (this.successToast = false), 3000);
        },
        onFinish: () => {
          this.is_submit_enabled = false;
          this.isLoading = false;
        },
      });
    },

    uploadImage(ref_name) {
      this.image_form.image = this.$refs.thumb.files[0];

      let user_token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
      let formData = new FormData();
      formData.append("file", this.image_form.image);
      axios
        .post("admin/images", formData, {
          headers: {
            Authorization: `Bearer ${user_token}`,
            "Content-Type": `multipart/form-data`,
          },
        })
        .then((response) => {});
    },
    addNewCategory() {
      let user_token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

      axios
        .post("/admin/categories/course", {
          category_name: this.new_category,
          headers: {
            _token: user_token,
          },
        })
        .then((response) => {
          this.all_course_categories = response.data[0];
          this.open = false;
        });
    },

    addVedioToList(vedio_id, title, thumnail) {
      this.form.vedio_components.push({
        id: this.vedio_components_id++,
        type: "vedio_select",
        thumbnail: thumnail,
        value: vedio_id,
        title: title,
      });
      this.vedio_components_id++;

      this.open2 = false;
    },
    reoveFromVedioComponents(id) {
      this.form.vedio_components = this.form.vedio_components.filter((e) => e.id !== id);
    },
    removeReportDocumentArea(id, index) {
      this.form.reports = this.form.reports.filter((e) => e.id !== id);
    },
    removeDocumentUploadArea(id, index) {
      this.form.viewings = this.form.viewings.filter((e) => e.id !== id);
    },

    clearAll() {
      this.form.name = "";
      this.form.summery = "";
      this.form.thumbnail = null;
      this.form.categories = [];
      this.form.keywords = [];
      this.form.advanced = [];
      this.form.status = 1; // 1 = publish 0 = not active
      this.form.vedio_components = [];
      this.form.reports = [];
      this.form.viewings = [];

      this.image_formimage = null;

      this.vedio_components_id = 1;
      this.viewings_id = 1;
      this.reports_id = 1;
      this.category_component = [];
      this.category_component_id = 1;
      this.report_name = null;
      this.new_category = null;
      this.thumb_upload = true;
      this.thumb_link = "";
    },
    countText(type, modl) {
      let max = 0;
      let now = 0;

      if (type == 1) {
        max = 50; //for title
        now = this.form.name.length;
      } else if (type == 2) {
        max = 50; //for title
      }

      return now;
    },
    validate() {
      let approve = true;
      this.form.reports.forEach((element, index) => {
        if (this.form.reports[index].type == "text") {
          if (
            this.form.reports[index].title == "" ||
            this.form.reports[index].description == null
          ) {
            approve = false;
          }
        } else {
          if (
            this.form.reports[index].title == "" ||
            this.form.reports[index].file_name == null ||
            this.form.reports[index].description == ""
          ) {
            approve = false;
          }
        }
      });
      this.form.viewings.forEach((element, index) => {
        if (
          this.form.viewings[index].title === "" ||
          this.form.viewings[index].file_name == null
        ) {
          approve = false;
        }
      });
      this.form.vedio_components.forEach((element, index) => {
        if (this.form.vedio_components[index].value == "") {
          approve = false;
        }
      });

      if (this.form.name == "") {
        this.fe_erors.title = true;
        approve = false;
      } else {
        this.fe_erors.title = false;
      }
      if (this.form.summery == "") {
        this.fe_erors.summery = true;
        approve = false;
      } else {
        this.fe_erors.summery = false;
      }
      if (this.form.thumbnail == null && this.form.old_thumb != 1) {
        this.fe_erors.thumbnail = true;
        this.thumb_er = true;
        approve = false;
      } else {
        this.fe_erors.thumbnail = false;
      }
      if (this.form.categories.length <= 0) {
        this.fe_erors.categories = true;
        approve = false;
      } else {
        this.fe_erors.categories = false;
      }
      if (this.form.keywords.length <= 0) {
        this.fe_erors.keywords = true;
        approve = false;
      } else {
        this.fe_erors.keywords = false;
      }

      if (!approve) this.requiredToast = true;
      if (approve) this.requiredToast = false;
      return approve;
    },
     formatdate(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      return formattedDate; //20171019
    },
  },
};
</script>
