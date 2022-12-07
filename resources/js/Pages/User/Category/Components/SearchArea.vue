<template>
  <div>
    <div class="search-form">
      <button class="btn btn-link" v-if="back_button" @click="goBack">
        <img :src="'/images/icon-back.svg'" alt="" />カテゴリー検索TOPへ戻る
      </button>
      <input type="text" class="form-control" v-model="keyword" placeholder="タイトル・キーワード・カテゴリー" @keypress.enter="searchFromKeyword"/>
    </div>
    <div class="category-area">
      <button class="btn btn-category" @click="searchFromCategory('all')">全て</button>
        <button
          class="btn btn-category category-tooltip"
          v-for="(category, index) in categories"
          :key="index"
          @click="searchFromCategory(category.id)"
        >
          <span class="text">{{ category.name }}</span>
          <span class="tooltiptext">{{ category.name }}</span>
        </button>
    </div>
  </div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";

export default {
  props: {
    categories: Object,
    back_button:Boolean
  },
  data() {
    return {
      keyword : '',
    }
  },

  methods: {
    searchFromCategory(id) {
      Inertia.get("/category/search/" + id);
    },
    searchFromKeyword() {
      Inertia.get("/category/keyword/" + this.keyword);
    },
    goBack() {
      Inertia.get("/category");
    },
  },
};
</script>
