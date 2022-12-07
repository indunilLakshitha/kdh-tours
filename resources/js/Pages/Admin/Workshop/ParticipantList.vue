<template>
  <AppLayoutAdmin>
    <div class="content workshop-participant-list">
      <div class="page-title-area">
        <h1>参加者リスト</h1>
        <div class="search-form">
          <!-- <button class="btn btn-dark">資料URLコピー</button>
          <button class="btn btn-dark">ワークシートURLコピー</button> -->
          <button class="btn btn-primary">CSVファイルダウンロード</button>
        </div>
      </div>
      <div class="page-card scrollable">
        <table class="table workshop-table">
          <thead>
            <tr>
              <th>No</th>
              <th>申込日時</th>
              <th>名前</th>
              <th>ふりがな</th>
              <th>会社名</th>
              <th>メールアドレス</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(participant, index) in participants" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ formatdate(participant.created_at) }}</td>
              <td>{{ participant.user.name }}</td>
              <td>{{ participant.user.name_in_furigana }}</td>
              <td>
                <div class="d-block">
                  <span>{{ participant.user.company.name }}</span>
                  <small v-if="participant.user.user_type == 1">担当者</small>
                </div>
              </td>
              <td>
                <div class="d-flex">
                  <img :src="'/images/icon-email.svg'" alt="" />{{
                    participant.user.email
                  }}
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayoutAdmin>
</template>

<script>
import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin.vue";
import moment from "moment";

export default {
  props: {
    participants: Object,
  },
  components: { AppLayoutAdmin, moment },
  // Modal
  data() {
    return {
      open: false,
    };
  },
  methods: {
    formatdate(date) {
      const formattedDate = moment(date).format("YYYY/MM/DD");
      return formattedDate; //20171019
    },
  },
};
</script>
