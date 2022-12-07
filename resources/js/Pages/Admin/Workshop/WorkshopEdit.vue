<template>
  <AppLayoutAdmin>
    <div class="content workshop-create">
      <div class="page-title-area">
        <h1>ワークショップ投稿</h1>
        <div class="search-form">
          <label for="">ステータス選択</label>
          <select
            name=""
            id=""
            v-model="form.reception_status"
            class="form-select"
            @change="changeRecepientStatus($event)"
          >
            <option
              v-for="(res, index) in reception_status_values"
              :key="index"
              :value="res.value"
            >
              {{ res.text }}
            </option>
          </select>
        </div>
      </div>
      <div class="page-card">
        <div class="form" action="">
          <div class="form">
            <div class="form-group sml">
              <label
                >ワークショップ名
                <span>(50文字以内)</span>
              </label>
              <div class="input-wrapper">
                <input
                  type="text"
                  class="form-control"
                  v-model="form.name"
                  @input="validate()"
                />
                <div class="error" v-if="errors.name">
                  ワークショップ名が必須項目です。
                </div>
              </div>
            </div>
            <div class="form-group sml">
              <label>概要</label>
              <div class="input-wrapper">
                <textarea
                  name=""
                  id=""
                  class="form-control"
                  v-model="form.summery"
                  @input="validate(name)"
                ></textarea>
                <div class="error" v-if="errors.summery">概要が必須項目です。</div>
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
                    <option value="" disabled :selected="setDefaultSelected">
                      カテゴリーを選択
                    </option>
                    <option
                      v-for="(cat, index) in form.all_workshop_categories"
                      :key="index"
                      :value="cat.id"
                    >
                      {{ trimFileName(cat.name, 2) }}
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
                      <button @click="addNewCategory" class="btn btn-primary">
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
                    @click="removeCategories(cat.id, cat.cat_id)"
                    v-for="(cat, index) in category_component"
                    :key="index"
                    >{{ trimFileName(cat.name, 2) }}</span
                  >
                </div>
              </div>
            </div>
            <div class="form-group sml">
              <label>サムネイル<br />アップロード</label>
              <div class="input-wrapper">
                <div style="display: flex">
                  <div class="file-upload-wrapper">
                    <input
                      type="file"
                      class="form-control file-upload"
                      @change="
                        uploadThumbnail($event);
                        validate();
                      "
                      ref="thumb"
                    />
                    <div class="spinner-wrapper" v-if="thumb_loading">
                      <div class="lds-dual-ring"></div>
                    </div>
                    <label for="">サムネイルが必須項目です</label>
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
                    サムネイル 必須項目です。
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

            <div class="form-group sml">
              <label>詳細説明</label>
              <div class="input-wrapper">
                <textarea
                  name=""
                  id=""
                  class="form-control h-290"
                  v-model="form.detail_description"
                  @input="validate()"
                ></textarea>
                <div class="error" v-if="errors.detail_description">
                  詳細説明が必須項目です。
                </div>
              </div>
            </div>
            <div class="form-group sml">
              <label>開催日時</label>
              <div class="input-wrapper">
                <!-- <div class="input-wrapper date-icon"> -->
                <input
                  type="date"
                  class="form-control w-420"
                  v-model="form.opening_date"
                  :disabled="workshop.blocked"
                  @input="
                    validate();
                    validateDatesAndTimes();
                  "
                />
                <div class="error" v-if="errors.opening_date">
                  開催日時が必須項目です。
                </div>
                <div class="error" v-if="errors.old_start_date">開催日時が無効です。</div>
              </div>
            </div>
            <div class="form-group sml">
              <label>開催時間</label>
              <div class="input-wrapper">
                <input
                  type="time"
                  class="form-control w-420"
                  v-model="form.opening_time"
                  :disabled="workshop.blocked"
                  @input="
                    validate();
                    validateDatesAndTimes();
                  "
                />
                <div class="error" v-if="errors.opening_time">
                  開催時間が必須項目です。
                </div>
                <div class="error" v-if="errors.invalid_time">開催時間が無効です。</div>
              </div>
            </div>
            <div class="form-group sml">
              <label>終了日時</label>
              <div class="input-wrapper">
                <!-- <div class="input-wrapper date-icon"> -->
                <input
                  type="date"
                  class="form-control w-420"
                  v-model="form.closing_date"
                  @input="
                    validate();
                    validateDatesAndTimes();
                  "
                />
                <div class="error" v-if="errors.closing_date">
                  終了日時が必須項目です。
                </div>
                <div class="error" v-if="errors.old_closing_date">
                  開催日時が無効です。
                </div>
              </div>
            </div>
            <div class="form-group sml">
              <label> 終了時間</label>
              <div class="input-wrapper">
                <input
                  type="time"
                  class="form-control w-420"
                  v-model="form.closing_time"
                  @input="
                    validate();
                    validateDatesAndTimes();
                  "
                />
                <div class="error" v-if="errors.closing_time">
                  終了時間が必須項目です。
                </div>
                <div class="error" v-if="errors.invalid_time">終了時間が無効です</div>
              </div>
            </div>
            <div class="form-group sml">
              <label>開催場所</label>
              <div class="input-wrapper">
                <select name="" v-model="form.venue" class="form-select w-420">
                  <option
                    v-for="(ven, index) in venue_values"
                    :key="index"
                    :value="ven.value"
                  >
                    {{ ven.text }}
                  </option>
                </select>
                <div class="error" v-if="errors.venue">開催場所が必須項目です。</div>
              </div>
            </div>
            <div class="form-group sml" v-if="form.venue == 1">
              <label>URL</label>
              <div class="input-wrapper">
                <input
                  type="text"
                  class="form-control"
                  placeholder="https://us06web.zoom.us/00000000000000000000000000"
                  v-model="form.place_url"
                  @input="
                    validate();
                    validateUrl();
                  "
                />
                <div class="error" v-if="errors.place_url">URLが必須項目です。</div>
                <div class="error" v-if="errors.place_url_invalid">
                  場所URL 無効なリンク
                </div>
              </div>
            </div>
            <div class="form-group sml" v-if="form.venue == 2">
              <label>場所</label>
              <div class="input-wrapper">
                <input
                  type="text"
                  class="form-control"
                  placeholder=""
                  v-model="form.place_url"
                />
                <div class="error" v-if="errors.place_url">場所が必須項目です。</div>
              </div>
            </div>
            <div class="form-group sml">
              <label>場所補足</label>
              <div class="input-wrapper">
                <textarea
                  name=""
                  id=""
                  v-model="form.place_suppliment"
                  @input="validate()"
                  class="form-control h-270"
                  placeholder="ウェビナーID：000 000 000
パスコード：abcdefg

※参加の際にIDを求められた際には上記をご入力ください。
※入場ができないなどのトラブルは、「ブラウザの種類」「アプリのアップデート」などの可能性がございます。
　事前にご参考ページをご覧いただければ幸いです。
　https://symphonict.nesic.co.jp/workingstyle/zoom/entering_trouble/
※その他、入場などZoomトラブルに関するお問い合わせは以下、運営担当者にご連絡ください。
会社名　ワークショップ運営事務局 担当者名：メールアドレス"
                ></textarea>
                <div class="error" v-if="errors.place_suppliment">
                  場所補足が必須項目です。
                </div>
              </div>
            </div>
            <div class="form-group sml">
              <label>定員数</label>
              <div class="input-wrapper">
                <input
                  type="number"
                  class="form-control"
                  v-model="form.capacity"
                  @input="
                    form.capacity = justNumbers(form.capacity);
                    validate();
                  "
                />
                <div class="error" v-if="errors.capacity">定員数が必須項目です。</div>
              </div>
            </div>
            <div class="form-group sml">
              <label>講師略歴</label>
              <div class="input-wrapper">
                <textarea
                  name=""
                  id=""
                  class="form-control h-270"
                  @input="validate()"
                  v-model="form.instructor_profile"
                ></textarea>
                <div class="error" v-if="errors.instructor_profile">
                  講師略歴が必須項目です。
                </div>
              </div>
            </div>
            <div class="sub-heading">事前課題・事前学習</div>
            <div class="form-group align-item-fs sml">
              <label>事前課題</label>
              <div class="flex-column">
                <div class="row-bordered">
                  <div class="label">
                    事前課題（レポート提出）を促しましょう。順番を変えたりカスタマイズ可能です。
                  </div>
                </div>
                <div v-for="(assignment, index) in form.assignments" :key="index">
                  <div class="row-bordered" v-if="assignment.type == 'document'">
                    <img
                      :src="'/images/icon-draggable.svg'"
                      class="icon-draggable"
                      alt=""
                    />
                    <div class="w-100">
                      <div class="upload-flex align-item-flex-start">
                        <div class="input-wrapper">
                          <textarea
                            name=""
                            id=""
                            class="form-control h-64"
                            v-model="form.assignments[index].title"
                          >
資料ダウンロードのタイトル</textarea
                          >
                          <div
                            class="error"
                            v-if="!form.assignments[index].title.length > 0"
                          >
                            必須項目です。
                          </div>
                        </div>
                        <div>
                          <div class="file-upload-wrapper">
                            <input
                              type="file"
                              class="form-control file-upload file"
                              @change="uploadDocument(1, $event, index)"
                            />
                            <div v-if="form.assignments[index]">
                              <label
                                for=""
                                v-if="!form.assignments[index].file_name.length > 0"
                                >ファイルをアップロード</label
                              >

                              <label
                                for=""
                                v-if="form.assignments[index].file_name.length > 0"
                                >{{
                                  trimFileName(form.assignments[index].file_name, 1)
                                }}</label
                              >
                            </div>
                            <div
                              class="spinner-wrapper"
                              v-if="form.assignments[index].loading"
                            >
                              <div class="lds-dual-ring"></div>
                            </div>
                          </div>
                          <div
                            class="error ml-6"
                            v-if="!form.assignments[index].url.length > 0"
                          >
                            必須項目です。
                          </div>
                        </div>
                      </div>
                      <textarea
                        name=""
                        id=""
                        class="form-control h-158"
                        v-model="form.assignments[index].description"
                      >
資料ダウンロードの説明文</textarea
                      >
                      <div
                        class="error"
                        v-if="!form.assignments[index].description.length > 0"
                      >
                        必須項目です。
                      </div>
                    </div>
                    <img
                      @click="
                        removeFromAssignmentArea(assignment.id, assignment.assignment_id)
                      "
                      :src="'/images/icon-close-circle.svg'"
                      class="icon-close"
                      alt=""
                    />
                  </div>
                  <div class="row-bordered" v-if="assignment.type == 'text'">
                    <img
                      :src="'/images/icon-draggable.svg'"
                      class="icon-draggable"
                      alt=""
                    />
                    <div class="w-100">
                      <div class="upload-flex align-item-flex-start">
                        <div class="input-wrapper">
                          <textarea
                            name=""
                            id=""
                            class="form-control h-64"
                            v-model="form.assignments[index].title"
                          >
資料ダウンロードのタイトル</textarea
                          >
                          <div
                            class="error"
                            v-if="!form.assignments[index].title.length > 0"
                          >
                            必須項目です。
                          </div>
                        </div>
                        <div></div>
                      </div>
                      <textarea
                        name=""
                        id=""
                        class="form-control h-158"
                        v-model="form.assignments[index].description"
                      >
 
資料ダウンロードの説明文</textarea
                      >
                      <div
                        class="error"
                        v-if="!form.assignments[index].description.length > 0"
                      >
                        必須項目です。
                      </div>
                    </div>
                    <img
                      @click="
                        removeFromAssignmentArea(assignment.id, assignment.assignment_id)
                      "
                      :src="'/images/icon-close-circle.svg'"
                      class="icon-close"
                      alt=""
                    />
                  </div>
                </div>

                <!-- <div class="row-bordered">
                  <button class="btn btn-link" @click="addAssignmentArea(0)">
                    <img
                      :src="'/images/icon-plus-circle-sml.svg'"
                      alt=""
                    />テキストエリアを追加する
                  </button>
                </div> -->
                <div class="row-bordered">
                  <button class="btn btn-link" @click="addAssignmentArea(1)">
                    <img
                      :src="'/images/icon-plus-circle-sml.svg'"
                      alt=""
                    />資料ダウンロードを追加する
                  </button>
                </div>
              </div>
            </div>
            <div class="form-group align-item-fs sml">
              <label>事前学習</label>
              <div class="flex-column">
                <div class="row-bordered">
                  <div class="label">事前に学習して欲しい動画・講座を促しましょう。</div>
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
                    <img :src="'/images/icon-plus-circle-sml.svg'" alt="" />追加する
                  </button>
                  <!-- Video add modal -->
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
              </div>
            </div>
            <!--  -->
            <div class="sub-heading">参加後設定</div>
            <div class="form-group align-item-flex-start sml">
              <label class="mt-3">レポート提出</label>
              <div class="flex-column">
                <div
                  class="row-bordered"
                  v-for="(report, index) in form.reports"
                  :key="index"
                >
                  <img
                    :src="'/images/icon-draggable.svg'"
                    class="icon-draggable"
                    alt=""
                  />
                  <div class="w-100">
                    <div class="upload-flex align-item-flex-start">
                      <div class="input-wrapper">
                        <textarea
                          name=""
                          id=""
                          class="form-control h-64"
                          v-model="form.reports[index].title"
                        >
レポートのタイトル</textarea
                        >
                        <div class="error" v-if="!form.reports[index].title.length > 0">
                          必須項目です。
                        </div>
                      </div>
                      <div>
                        <div class="file-upload-wrapper">
                          <input
                            type="file"
                            class="form-control file-upload file"
                            @change="uploadDocument(2, $event, index)"
                          />
                          <label for="" v-if="!form.reports[index].file_name.length > 0"
                            >ファイルをアップロード</label
                          >

                          <label for="" v-if="form.reports[index].file_name.length > 0">{{
                            form.reports[index].file_name
                          }}</label>
                          <div class="spinner-wrapper" v-if="form.reports[index].loading">
                            <div class="lds-dual-ring"></div>
                          </div>
                        </div>
                        <div
                          class="error ml-6"
                          v-if="!form.reports[index].file_name.length > 0"
                        >
                          必須項目です。
                        </div>
                      </div>
                    </div>
                    <textarea
                      name=""
                      id=""
                      class="form-control h-158"
                      v-model="form.reports[index].description"
                    >
レポートの説明文</textarea
                    >
                    <div class="error" v-if="!form.reports[index].description.length > 0">
                      必須項目です。
                    </div>
                  </div>
                  <img
                    :src="'/images/icon-close-circle.svg'"
                    class="icon-close"
                    alt=""
                    @click="removeFromReportArea(report.id)"
                  />
                </div>
                <div class="row-bordered">
                  <button class="btn btn-link" @click="addReportArea()">
                    <img
                      :src="'/images/icon-plus-circle-sml.svg'"
                      alt=""
                    />レポートを追加する
                  </button>
                </div>
              </div>
            </div>
            <!--  -->
            <div v-for="(document, index) in form.documents" :key="index">
              <div
                class="form-group align-item-fs sml mb-0"
                v-if="document.type == 'document'"
              >
                <label>資料アップロード</label>
                <div class="flex-column">
                  <div class="row-bordered">
                    <img
                      :src="'/images/icon-draggable.svg'"
                      class="icon-draggable"
                      alt=""
                    />
                    <div class="w-100">
                      <div class="upload-flex align-item-flex-start">
                        <div class="input-wrapper">
                          <textarea
                            name=""
                            id=""
                            class="form-control h-64"
                            v-model="form.documents[index].title"
                          >
タイトル</textarea
                          >
                          <div
                            class="error"
                            v-if="!form.documents[index].title.length > 0"
                          >
                            必須項目です。
                          </div>
                        </div>
                        <div>
                          <div class="file-upload-wrapper">
                            <input
                              type="file"
                              class="form-control file-upload"
                              @change="uploadDocument(3, $event, index)"
                            />
                            <label
                              for=""
                              v-if="!form.documents[index].file_name.length > 0"
                              >ファイルをアップロード</label
                            >

                            <label
                              for=""
                              v-if="form.documents[index].file_name.length > 0"
                              >{{ form.documents[index].file_name }}</label
                            >
                            <div
                              class="spinner-wrapper"
                              v-if="form.documents[index].loading"
                            >
                              <div class="lds-dual-ring"></div>
                            </div>
                          </div>
                          <div
                            class="error ml-6"
                            v-if="!form.documents[index].file_name.length > 0"
                          >
                            必須項目です。
                          </div>
                        </div>
                      </div>
                    </div>
                    <img
                      :src="'/images/icon-close-circle.svg'"
                      @click="removeFromDocumentArea(document.id)"
                      class="icon-close"
                      alt=""
                    />
                  </div>
                </div>
              </div>
              <div
                class="form-group align-item-fs sml"
                v-if="document.type == 'worksheet'"
              >
                <label>ワークシート<br />アップロード</label>
                <div class="flex-column">
                  <div class="row-bordered">
                    <img
                      :src="'/images/icon-draggable.svg'"
                      class="icon-draggable"
                      alt=""
                    />
                    <div class="w-100">
                      <div class="upload-flex align-item-flex-start">
                        <div class="input-wrapper">
                          <textarea
                            name=""
                            id=""
                            class="form-control h-64"
                            v-model="form.documents[index].title"
                          >
タイトル</textarea
                          >
                          <div
                            class="error"
                            v-if="!form.documents[index].title.length > 0"
                          >
                            必須項目です。
                          </div>
                        </div>
                        <div>
                          <div class="file-upload-wrapper">
                            <input
                              type="file"
                              class="form-control file-upload"
                              @change="uploadDocument(3, $event, index)"
                            />
                            <label
                              for=""
                              v-if="!form.documents[index].file_name.length > 0"
                              >ファイルをアップロード</label
                            >

                            <label
                              for=""
                              v-if="form.documents[index].file_name.length > 0"
                              >{{ form.documents[index].file_name }}</label
                            >
                            <div
                              class="spinner-wrapper"
                              v-if="form.documents[index].loading"
                            >
                              <div class="lds-dual-ring"></div>
                            </div>
                          </div>
                          <div
                            class="error ml-6"
                            v-if="!form.documents[index].file_name.length > 0"
                          >
                            必須項目です。
                          </div>
                        </div>
                      </div>
                    </div>
                    <img
                      :src="'/images/icon-close-circle.svg'"
                      @click="removeFromDocumentArea(document.id)"
                      class="icon-close"
                      alt=""
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group align-item-fs sml">
              <label></label>
              <div class="flex-column">
                <div class="row-bordered">
                  <button class="btn btn-link" @click="addDocumentArea(1)">
                    <img :src="'/images/icon-plus-circle-sml.svg'" alt="" />資料を追加する
                  </button>
                </div>
                <div class="row-bordered">
                  <button class="btn btn-link" @click="addDocumentArea(2)">
                    <img
                      :src="'/images/icon-plus-circle-sml.svg'"
                      alt=""
                    />ワークシートを追加する
                  </button>
                </div>
              </div>
            </div>
            <!-- <div class="form-group align-item-fs sml">
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
            </div> -->
            <!--  -->
          </div>
        </div>
      </div>
      <div class="btn-area">
        <button class="btn btn-secondary" @click="submitAndPublish(0)" :disabled = workshop.blocked>
          非公開で保存
        </button>
        <button class="btn btn-primary" @click="submitAndPublish(1)">公開する</button>
      </div>
    </div>
    <div class="spinner-wrapper" v-if="loading">
      <div class="lds-dual-ring"></div>
    </div>
  </AppLayoutAdmin>
</template>

<script>
import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin.vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import { useForm } from "@inertiajs/inertia-vue3";
import moment from "moment";

export default {
  props: {
    courses: Object,
    categories: Object,
    workshop: Object,
    id: String,
  },
  components: { AppLayoutAdmin, moment },
  // Modal
  data() {
    return {
      open: false,
      form: useForm({
        id: "",
        name: "",
        summery: "",
        thumbnail: "",
        detail_description: "",
        opening_date: "",
        opening_time: "",
        closing_date: "",
        closing_time: "",
        venue: "",
        place_url: "",
        place_suppliment: "",
        capacity: "",
        instructor_profile: "",
        reception_status: 1,
        status: 1, // 1 = publish 0 = not active
        all_workshop_categories: this.categories,
        assignments: [],
        removed_assignments: [],
        categories: [],
        reports: [],
        courses: [],
        documents: [],
      }),
      loading: false,
      reception_status_values: [
        { value: 1, text: "受付中" },
        { value: 0, text: "not 受付中" },
      ],
      venue_values: [
        { value: 1, text: "オンライン" },
        { value: 2, text: "会場" },
      ],
      course_components: [],
      category_component: [],
      category_component_id: 0,
      validated: false,
      assignment_id: 0,
      new_category: "",
      report_id: 0,
      course_component_id: 0,
      document_id: 0,
      course_modal: false,
      setDefaultSelected: true,
      thumb_loading: false,
      errors: {
        name: false,
        summery: false,
        thumbnail: false,
        thumbnail_invalid: false,
        thumbnail_size: false,
        detail_description: false,
        opening_date: false,
        opening_time: false,
        closing_date: false,
        closing_time: false,
        venue: false,
        place_url: false,
        place_url_invalid: false,
        place_suppliment: false,
        capacity: false,
        instructor_profile: false,
        invalid_date_times: false,
        old_date_times: false,
        categories: false,
        old_start_date: false,
        old_closing_date: false,
        invalid_time: false,
      },
    };
  },
  created() {
    this.form.name = this.workshop.name;
    this.form.id = this.workshop.id;
    this.form.summery = this.workshop.summery;
    this.form.thumbnail = this.workshop.thumbnail;
    this.form.detail_description = this.workshop.detail_description;
    this.form.opening_date = this.workshop.opening_date;
    this.form.opening_time = this.workshop.opening_time;
    this.form.closing_date = this.workshop.closing_date;
    this.form.closing_time = this.workshop.closing_time;
    this.form.closing_time = this.workshop.closing_time;
    this.form.venue = this.workshop.venue;
    this.form.place_url = this.workshop.place_url;
    this.form.place_suppliment = this.workshop.place_suppliment;
    this.form.capacity = this.workshop.capacity;
    this.form.instructor_profile = this.workshop.instructor_profile;
    this.form.reception_status = this.workshop.reception_status;
    this.form.status = this.workshop.status;

    // load categories
    this.workshop.categories.forEach((e) => {
      this.category_component.push({
        id: this.category_component_id++,
        name: e.category.name,
        value: e.category.id,
        cat_id: e.category.id,
      });
      this.form.categories.push(e.category.id);
      this.category_component_id++;
    });

    // load courses
    this.workshop.courses.forEach((e) => {
      this.addCourseToList(
        e.workshop_course.id,
        e.workshop_course.title,
        e.workshop_course.thumbnail
      );
    });

    // load assignments
    this.workshop.assignments.forEach((e) => {
      if (e.type == 2) {
        this.form.assignments.push({
          id: this.assignment_id++,
          type: "document",
          title: e.title,
          description: e.description,
          file_name: e.file_name,
          loading: false,
          url: e.url,
          assignment_id: e.id,
          exist: true,
        });
      }
      // else {
      //   this.form.assignments.push({
      //     id: this.assignment_id++,
      //     type: "text",
      //     title: e.title,
      //     description: e.description,
      //     url: "",
      //     file_name: null,
      //   });
      // }
    });

    // load reports
    this.workshop.reports.forEach((e) => {
      this.form.reports.push({
        id: this.report_id++,
        type: "report",
        title: e.title,
        description: e.description,
        loading: false,
        file_name: e.file_name,
        url: e.url,
      });
    });

    // load documents
    this.workshop.documents.forEach((e) => {
      if (e.type == 1) {
        this.form.documents.push({
          id: this.document_id++,
          type: "document",
          title: e.title,
          description: e.description,
          loading: false,
          file_name: e.file_name,
          url: "",
        });
      } else if (e.type == 2) {
        this.form.documents.push({
          id: this.document_id++,
          type: "worksheet",
          title: e.title,
          description: e.description,
          file_name: e.file_name,
          loading: false,
          url: "",
        });
      }
    });
  },
  methods: {
    submitAndPublish(status) {
      this.validate();
      this.validated = true;
      this.form.status = status;
      if (this.validate() && this.validateDatesAndTimes()) {
        this.form.post(route("admin.workshop.update"), {
          onSuccess: () => {
            console.log("success");
          },
          onFinish: () => {
            console.log("failed");
          },
        });
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
          .post("/admin/workshop/thumb", formData, {
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
              console.log("failed");
              this.thumb_loading = false;
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
          if (!this.form.summery.length > 0) {
            this.errors.summery = true;
            required = 1;
          } else {
            this.errors.summery = false;
          }
          if (!this.form.thumbnail.length > 0) {
            this.errors.thumbnail = true;
            required = 1;
          } else {
            this.errors.thumbnail = false;
          }

          if (!this.form.detail_description.length > 0) {
            this.errors.detail_description = true;
            required = 1;
          } else {
            this.errors.detail_description = false;
          }
          if (!this.form.opening_date.length > 0) {
            this.errors.opening_date = true;
            required = 1;
          } else {
            this.errors.opening_date = false;
          }
          if (!this.form.opening_time.length > 0) {
            this.errors.opening_time = true;
            required = 1;
          } else {
            this.errors.opening_time = false;
          }
          if (!this.form.closing_date.length > 0) {
            this.errors.closing_date = true;
            required = 1;
          } else {
            this.errors.closing_date = false;
          }
          if (!this.form.closing_time.length > 0) {
            this.errors.closing_time = true;
            required = 1;
          } else {
            this.errors.closing_time = false;
          }
          if (!this.form.venue > 0) {
            this.errors.venue = true;
            required = 1;
          } else {
            this.errors.venue = false;
          }
          if (!this.form.place_url.length > 0) {
            this.errors.place_url = true;
            required = 1;
          } else {
            this.errors.place_url = false;
          }
          if (!this.form.place_suppliment.length > 0) {
            this.errors.place_suppliment = true;
            required = 1;
          } else {
            this.errors.place_suppliment = false;
          }
          if (!this.form.capacity > 0) {
            this.errors.capacity = true;
            required = 1;
          } else {
            this.errors.capacity = false;
          }
          if (!this.form.instructor_profile.length > 0) {
            this.errors.instructor_profile = true;
            required = 1;
          } else {
            this.errors.instructor_profile = false;
          }
          if (!this.form.categories.length > 0) {
            this.errors.categories = true;
            required = 1;
          } else {
            this.errors.categories = false;
          }
        }
      } else {
        var data = "this.form." + model;
        if (!data.length > 0) {
          data = true;
        } else {
          data = false;
        }
      }

      if (required == 1) {
        return false;
      } else {
        return true;
      }
    },
    changeRecepientStatus(event) {
      this.form.reception_status = event.target.value;
      console.log(this.form.reception_status);
    },
    addAssignmentArea(type) {
      if (type == 1) {
        this.form.assignments.push({
          id: this.assignment_id++,
          type: "document",
          title: "",
          description: "",
          file_name: "",
          loading: false,
          exist: false,
          assignment_id: 0,
          url: "",
        });
      } else {
        this.form.assignments.push({
          id: this.assignment_id++,
          type: "text",
          title: "",
          description: "",
          file_name: "",
          url: "",
        });
      }

      console.log(this.form.assignments);
    },
    removeFromAssignmentArea(id, assignment_id) {
      this.form.assignments = this.form.assignments.filter((e) => e.id !== id);
      if (assignment_id != 0) {
        this.form.removed_assignments.push(assignment_id);
      }
    },

    addReportArea(type) {
      this.form.reports.push({
        id: this.report_id++,
        type: "report",
        title: "",
        description: "",
        loading: false,
        file_name: "",
        url: "",
      });
    },
    removeFromReportArea(id) {
      this.form.reports = this.form.reports.filter((e) => e.id !== id);
    },
    addDocumentArea(type) {
      if (type == 1) {
        this.form.documents.push({
          id: this.document_id++,
          type: "document",
          title: "",
          description: "",
          loading: false,
          file_name: "",
          url: "",
        });
      } else if (type == 2) {
        this.form.documents.push({
          id: this.document_id++,
          type: "worksheet",
          title: "",
          file_name: "",
          loading: false,
          description: "",
          url: "",
        });
      }
    },
    removeFromDocumentArea(id) {
      this.form.documents = this.form.documents.filter((e) => e.id !== id);
    },

    uploadDocument(type, event, index) {
      let user_token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
      let formData = new FormData();
      if (type == 1) {
        this.form.assignments[index].loading = true;
      }
      if (type == 2) {
        this.form.reports[index].loading = true;
      }
      if (type == 3) {
        this.form.documents[index].loading = true;
      }

      formData.append("document", event.target.files[0]);
      axios
        .post("/admin/workshop/document", formData, {
          headers: {
            _token: user_token,
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          console.log(response);
          if (response.data.success == true) {
            if (type == 1) {
              this.form.assignments[index].url = response.data.url.url;
              this.form.assignments[index].loading = false;
              this.form.assignments[index].file_name = response.data.url.url.split(
                "temp/"
              )[1];
            } else if (type == 2) {
              this.form.reports[index].url = response.data.url.url;
              this.form.reports[index].loading = false;
              this.form.reports[index].file_name = response.data.url.url.split(
                "temp/"
              )[1];
            } else if (type == 3) {
              this.form.documents[index].url = response.data.url.url;
              this.form.documents[index].loading = false;
              this.form.documents[index].file_name = response.data.url.url.split(
                "temp/"
              )[1];
            }
            console.log(this.form.assignments);
          } else {
            console.log("failed");
          }
        });
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
        .post("/admin/workshop/category", {
          category_name: this.new_category,
          headers: {
            _token: user_token,
          },
        })
        .then((response) => {
          this.form.all_workshop_categories = response.data[0];
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
          value: null,
          cat_id: event.target.value,
        });
        this.form.categories.push(event.target.value);
        this.category_component_id++;
      }

      this.$refs.catDropdown.value = "";
    },
    removeCategories(id, cat_id) {
      this.category_component = this.category_component.filter((e) => e.id !== id);
      this.form.categories = this.form.categories.filter((e) => e !== cat_id);

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
    validateUrl() {
      var pattern = new RegExp(
        "^(https?:\\/\\/)?" + // protocol
          "((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|" + // domain name
          "((\\d{1,3}\\.){3}\\d{1,3}))" + // OR ip (v4) address
          "(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*" + // port and path
          "(\\?[;&a-z\\d%_.~+=-]*)?" + // query string
          "(\\#[-a-z\\d_]*)?$",
        "i"
      ); // fragment locator
      if (!!pattern.test(this.form.place_url)) {
        this.errors.place_url_invalid = false;
      } else {
        this.errors.place_url_invalid = true;
      }
      console.log(this.errors.place_url_invalid);
    },
    validateDatesAndTimes() {
      var date = new Date();
      var today = date.toJSON().slice(0, 10);
      var today_time_in_min = date.getHours() * 60 + date.getMinutes();
      var required = 0;

      var closing_time_in_mn = new Date(this.closing_time).getHours();

      if (!this.workshop.blocked) {
        if (this.form.opening_date.length > 0) {
          if (this.form.opening_date < today) {
            this.errors.old_date_times = true;
            this.errors.old_start_date = true;
            required = 1;
          } else {
            this.errors.old_start_date = false;
          }
        } else {
          this.errors.old_start_date = false;
        }
      }


      if (this.form.closing_date.length > 0) {
        if (this.form.closing_date < today) {
          this.errors.old_closing_date = true;
          required = 1;
        } else {
          this.errors.old_closing_date = false;
        }
      } else {
        this.errors.old_closing_date = false;
      }

      // if (this.form.closing_date.length > 0 && this.form.opening_date.length > 0) {
      //   if (this.form.closing_date < this.form.opening_date) {
      //     this.errors.old_date_times = true;
      //     this.errors.old_closing_date = true;
      //     required = 1;
      //   } else {
      //     this.errors.old_closing_date = false;
      //   }
      // } else if (this.form.closing_date.length > 0) {
      //   if (this.form.closing_date < today) {
      //     this.errors.old_closing_date = true;
      //     required = 1;
      //   } else {
      //     this.errors.old_closing_date = false;
      //   }
      // } else {
      //   this.errors.old_closing_date = false;
      // }

      if (this.form.closing_date.length > 0 && this.form.opening_date.length > 0) {
        if (this.form.opening_time.length > 0 && this.form.closing_time.length > 0) {
          if (this.form.opening_date == this.form.closing_date) {
            if (this.form.opening_time < this.form.closing_time) {
              this.errors.invalid_time = false;
            } else {
              this.errors.invalid_time = true;
              required = 1;
            }
          } else if (this.form.opening_date < this.form.closing_date) {
            this.errors.invalid_time = false;
          } else {
            this.errors.old_start_date = true;
            this.errors.old_closing_date = true;
            required = 1;
          }
        }
      }
      if (required == 1) {
        return false;
      } else {
        return true;
      }
    },
    justNumbers(string) {
      var numsStr = string.toString().replace(/[^0-9]/g, "");
      return parseInt(numsStr);
    },
    trimFileName(filename, type) {
      var file_name_to_show;

      if (type == 1) {
        // files with ext
        var name = filename.substr(0, filename.lastIndexOf("."));
        var ext = filename.substr(filename.lastIndexOf(".") + 1);

        if (name.length > 15) {
          file_name_to_show = name.slice(0, 15) + "...." + ext;
        } else {
          file_name_to_show = name + "." + ext;
        }
        return file_name_to_show;
      } else {
        var name = filename;
        if (name.length > 15) {
          file_name_to_show = name.slice(0, 15) + "....";
        } else {
          file_name_to_show = name;
        }
        return file_name_to_show;
      }
    },
  },
};
</script>
