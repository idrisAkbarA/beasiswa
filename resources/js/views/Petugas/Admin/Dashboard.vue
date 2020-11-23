<template>
  <v-container class="pa-10">
    <v-row >
      <v-col>
        <v-card :height="100*1.2" :width="200*1.2">
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/100?random=5'"
          >
            <v-card-title>
              <span class="body 2">Belum Lengkap</span>
            </v-card-title>
            <v-card-text>
              <h1>{{isSubmit}}</h1>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
      <v-col>
        <v-card :height="100*1.2" :width="200*1.2">
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/100?random=2'"
          >
            <v-card-title>
              <span class="body 2">Belum Cek Berkas</span>
            </v-card-title>
            <v-card-text>
              <h1>{{berkas}}</h1>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
      <v-col>
        <v-card :height="100*1.2" :width="200*1.2">
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/100?random=1'"
          >
            <v-card-title>
              <span class="body 2">Belum Interview</span>
            </v-card-title>
            <v-card-text>
              <h1>{{interview}}</h1>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
      <v-col>
        <v-card :height="100*1.2" :width="200*1.2">
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/100?random=3'"
          >
            <v-card-title>
              <span class="body 2">Lulus Seleksi</span>
            </v-card-title>
            <v-card-text>
              <h1>{{lulus}}</h1>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
      <v-col>
        <v-card :height="100*1.2" :width="200*1.2">
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/100?random=4'"
          >
            <v-card-title>
              <span class="body 2">Beasiswa</span>
            </v-card-title>
            <v-card-text>
              <h1>{{beasiswa}}</h1>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      isSubmit: 0,
      interview: 0,
      berkas: 0,
      lulus: 0,
      beasiswa: 0
    };
  },
  created() {
    axios.get("/api/pemohon/count-submit").then(response => {
      this.isSubmit = response.data;
    });
    axios.get("/api/pemohon/count-interview").then(response => {
      this.interview = response.data;
    });
    axios.get("/api/pemohon/count-berkas").then(response => {
      this.berkas = response.data;
    });
    axios.get("/api/pemohon/count-lulus").then(response => {
      this.lulus = response.data;
    });
    axios.get("/api/beasiswa/count-beasiswa").then(response => {
      this.beasiswa = response.data;
    });
  }
};
</script>

<style>
</style>
