<template>
  <v-container>
      <v-row justify="center" v-if="permohonans.length<1">
          <h2>Tidak ada permohonan</h2>
      </v-row>
      <v-row v-for="(item,index) in permohonans" :key="index">
          <v-card class="ma-5" :color="checkColor(item)">
              <v-card-title>
                  {{item.beasiswa.nama}}
              </v-card-title>
              <v-card-text>
                  {{checkStatus(item)}}
              </v-card-text>
          </v-card>
      </v-row>
  </v-container>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
    created(){
        this.getUserPermohonan()
    },
  methods: {
      checkStatus(item){
          if(item.is_berkas_passed==0){
              return "Maaf permohonan anda didiskualifikasi"
          }
          if(item.is_interview_passed==0){
              return "Maaf permohonan anda didiskualifikasi"
          }
          if(item.is_survey_passed==0){
              return "Maaf permohonan anda didiskualifikasi"
          }
          if(item.is_survey_passed==0){
              return "Maaf permohonan anda didiskualifikasi"
          }
          if(item.is_selection_passed==1){
              return "Selamat permohonan anda lulus"
          }
          return "Permohonan anda sedang di proses"
      },
      checkColor(item){
          if(item.is_berkas_passed==0){
              return "red"
          }
          if(item.is_interview_passed==0){
              return "red"
          }
          if(item.is_survey_passed==0){
              return "red"
          }
          if(item.is_survey_passed==0){
              return "red"
          }
          if(item.is_selection_passed==1){
              return "green"
          }
          return "brown"
      },
    getUserPermohonan() {
      axios.get(this.url + "/api/pemohon/cek-isHas").then(response => {
        console.log(response.data);
        this.permohonans = response.data
      });
    }
  },
  computed: {
      ...mapState(['url']),
  },
  data() {
      return {
          permohonans:[],
          color:""
      }
  },
};
</script>

<style>
</style>