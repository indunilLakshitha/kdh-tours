<template>
    <AppLayout>
        <AdminHeader />
        <div class="layout">
            <Sidebar />
            <div class="content course-register">
                <div class="page-title-area">
                    <h1>講座登録</h1>
                </div>
                <div class="page-card">
                    <div class="form">
                        <div class="form-group sml">
                            <label>講座名<span>(50文字以内)</span></label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group sml">
                            <label>講座概要</label>
                            <textarea name="" id="" class="form-control"></textarea>
                        </div>
                        <div class="form-group align-item-fs sml">
                            <label>カテゴリー</label>
                            <div class="flex-column">
                                <div class="flex-row">
                                    <select name="" id="" class="form-control w-420">
                                        <option value=""></option>
                                        <option value=""></option>
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
                                            <input type="text" class="form-control">
                                            <button @click="open = false" class="btn btn-primary">追加</button>
                                        </div>
                                    </div>
                                </div>
                                <a href="" class="link">複数選択</a>
                                <div class="tag-area">
                                    <span class="tag">カテゴリー</span>
                                    <span class="tag">カテゴリー</span>
                                    <span class="tag">カテゴリー</span>
                                    <span class="tag">カテゴリー</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group sml">
                            <label>サムネイル<br/>アップロード</label>
                            <div class="file-upload-wrapper">
                                <input type="file" class="form-control file-upload">
                                <label for="">サムネイルをアップロード</label>
                            </div>
                        </div>
                        <div class="form-group align-item-fs sml">
                            <label>詳細設定</label>
                            <div class="flex-column">
                                <div class="row-bordered">
                                    <div class="label">講座のチャプター順に「大項目タイトル」と「動画」を選択してパッケージをつくりましょう。</div>
                                </div>
                                <div class="row-bordered">
                                    <img :src="'/images/icon-draggable.svg'" class="icon-draggable" alt="" />
                                    <img :src="'/images/course-thumb.png'" class="thumb" alt="" />
                                    <p class="para">タイトルタイトルタイトルタイトルイトルタイトルタイトルイトルタイトルタイトルタイトルタイトルタイトル</p>
                                    <img :src="'/images/icon-close-circle.svg'" class="icon-close" alt="" />
                                </div>
                                <div class="row-bordered">
                                    <img :src="'/images/icon-draggable.svg'" class="icon-draggable" alt="" />
                                    <textarea name="" id="" class="form-control w-680 h-64">大項目タイトル</textarea>
                                    <p class="char-count">(30文字以内)</p>
                                    <img :src="'/images/icon-close-circle.svg'" class="icon-close" alt="" />
                                </div>
                                <div class="row-bordered">
                                    <img :src="'/images/icon-draggable.svg'" class="icon-draggable" alt="" />
                                    <!-- <textarea name="" id="" class="form-control w-680 h-64">動画・講座選択</textarea> -->
                                    <select name="" id="" class="form-control w-680">
                                        <option value="" disabled selected>動画・講座選択</option>
                                        <option value=""></option>
                                    </select>
                                    <!-- <button class="btn btn-link">
                                        <img :src="'/images/icon-plus-circle-sml.svg'" alt="">
                                    </button> -->
                                    <img :src="'/images/icon-close-circle.svg'" class="icon-close" alt="" />
                                </div>
                                <div class="row-bordered">
                                    <button class="btn btn-link">
                                        <img :src="'/images/icon-plus-circle-sml.svg'" alt="">大項目タイトルを追加する
                                    </button>
                                </div>
                                <div class="row-bordered">
                                    <button class="btn btn-link">
                                        <img :src="'/images/icon-plus-circle-sml.svg'" alt="">動画・講座を追加する
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="sub-heading">レポート設定</div>
                        <div class="form-group align-item-fs sml">
                            <label>レポート項目</label>
                            <div class="flex-column">
                                <div class="row-bordered">
                                    <div class="label">レポート項目を追加してレポートページを作成しましょう。順番を変えたりカスタマイズ可能です。</div>
                                </div>
                                <div class="row-bordered">
                                    <img :src="'/images/icon-draggable.svg'" class="icon-draggable" alt="" />
                                    <div class="w-100">
                                        <textarea name="" id="" class="form-control h-64">テキストエリアのタイトル</textarea>
                                        <textarea name="" id="" class="form-control h-158">テキストエリアの説明文</textarea>
                                    </div>
                                    <img :src="'/images/icon-close-circle.svg'" class="icon-close" alt="" />
                                </div>
                                <div class="row-bordered">
                                    <img :src="'/images/icon-draggable.svg'" class="icon-draggable" alt="" />
                                    <div class="w-100">
                                        <div class="upload-flex">
                                            <textarea name="" id="" class="form-control h-64">資料ダウンロードのタイトル</textarea>
                                            <div class="file-upload-wrapper">
                                                <input type="file" class="form-control file-upload">
                                                <label for="">ファイルをアップロード</label>
                                            </div>
                                        </div>
                                        <textarea name="" id="" class="form-control h-158">資料ダウンロードの説明文</textarea>
                                    </div>
                                    <img :src="'/images/icon-close-circle.svg'" class="icon-close" alt="" />
                                </div>
                                <div class="row-bordered">
                                    <button class="btn btn-link">
                                        <img :src="'/images/icon-plus-circle-sml.svg'" alt="">テキストエリアを追加する
                                    </button>
                                </div>
                                <div class="row-bordered">
                                    <button class="btn btn-link">
                                        <img :src="'/images/icon-plus-circle-sml.svg'" alt="">資料ダウンロードを追加する
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="sub-heading">視聴後設定</div>
                        <div class="form-group align-item-fs sml mb-0">
                            <label>資料アップロード</label>
                            <div class="flex-column">
                                <div class="row-bordered">
                                    <img :src="'/images/icon-draggable.svg'" class="icon-draggable" alt="" />
                                    <div class="w-100">
                                        <div class="upload-flex">
                                            <textarea name="" id="" class="form-control h-64">タイトル</textarea>
                                            <div class="file-upload-wrapper">
                                                <input type="file" class="form-control file-upload">
                                                <label for="">ファイルをアップロード</label>
                                            </div>
                                        </div>
                                    </div>
                                    <img :src="'/images/icon-close-circle.svg'" class="icon-close" alt="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group align-item-fs sml">
                            <label>ワークシート<br/>アップロード</label>
                            <div class="flex-column">
                                <div class="row-bordered">
                                    <img :src="'/images/icon-draggable.svg'" class="icon-draggable" alt="" />
                                    <div class="w-100">
                                        <div class="upload-flex">
                                            <textarea name="" id="" class="form-control h-64">タイトル</textarea>
                                            <div class="file-upload-wrapper">
                                                <input type="file" class="form-control file-upload">
                                                <label for="">ファイルをアップロード</label>
                                            </div>
                                        </div>
                                    </div>
                                    <img :src="'/images/icon-close-circle.svg'" class="icon-close" alt="" />
                                </div>
                                <div class="row-bordered">
                                    <button class="btn btn-link">
                                        <img :src="'/images/icon-plus-circle-sml.svg'" alt="">資料を追加する
                                    </button>
                                </div>
                                <div class="row-bordered">
                                    <button class="btn btn-link">
                                        <img :src="'/images/icon-plus-circle-sml.svg'" alt="">ワークシートを追加する
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group align-item-fs sml">
                            <label>関連講座</label>
                            <div class="flex-column">
                                <div class="row-bordered">
                                    <div class="label">合わせて試聴してほしい講座を選んでください。（最大3つ）</div>
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
                                        <img :src="'/images/icon-plus-circle-sml.svg'" alt="">おすすめを選ぶ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-area">
                    <button class="btn btn-secondary">非公開で保存</button>
                    <button class="btn btn-primary">公開する</button>
                </div>
            </div>
        </div>
        <AdminFooter />
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import AdminHeader from "../../Layouts/AdminHeader.vue";
import Sidebar from "../../Layouts/Sidebar.vue";
import AdminFooter from "../../Layouts/AdminFooter.vue";

export default {
    components: { AdminHeader, AppLayout, Sidebar, AdminFooter },
    setup() {
        const form = {
            email: null,
            password: null,
        };

        function submit() {
            Inertia.post("/admin/login", form);
        }

        return { form, submit };
    },
    // Modal
    data() {
        return {
        open: false
        }
    }
};
</script>
