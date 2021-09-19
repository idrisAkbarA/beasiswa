<template>
  <v-container
    fluid
    class="main pa-0 ma-0"
  >
    <v-row
      class="fill-height"
      style="height:100%"
      align="center"
      v-if="beasiswa.length<1"
    >
      <v-col cols="12">
        <v-img
          class="mx-auto"
          max-width="340"
          :src="'/images/nothing.png'"
        ></v-img>
      </v-col>
      <v-col cols="12">
        <div style="width:100%">
          <h4 style="text-align:center">Maaf, belum ada beasiswa yang tersedia saat ini</h4>
        </div>
      </v-col>
    </v-row>
    <v-row
      v-else
      justify="center"
    >
      <v-card
        @click="goto(item)"
        ripple
        class="ma-3"
        :height="300*1.2"
        :width="200*1.2"
        v-for="(item,index) in beasiswa"
        :key="index"
      >
        <v-img
          gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
          :src="'https://picsum.photos/200/300?random='+index"
        >
          <template v-slot:placeholder>
            <v-skeleton-loader
              ref="skeleton"
              :loading="loading"
              type="image"
              class="ma-auto"
            ></v-skeleton-loader>
          </template>
          <v-card-title>
            <span>{{index+1}}</span>
            <v-spacer></v-spacer>
            <span class="caption">Tersedia</span>
          </v-card-title>
          <v-card-text>
            <h4>{{item.nama.length>100 ? item.nama.substring(0,100) + " ..." : item.nama}}</h4>
          </v-card-text>
        </v-img>
      </v-card>
    </v-row>

    <!-- Snackbar -->
    <v-snackbar
      v-model="snackbar.show"
      :timeout="2000"
    >
      {{ snackbar.message }}
      <template v-slot:action="{ attrs }">
        <v-btn
          :color="snackbar.color"
          text
          v-bind="attrs"
          @click="snackbar.show = false"
        >Close</v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  methods: {
    ...mapActions(["getBeasiswaActive"]),

    goto(item) {
      this.$router.push({
        path: "/mahasiswa/daftar-beasiswa/" + item.id,
      });
    },
  },
  created() {
    this.getBeasiswaActive();
  },
  computed: {
    ...mapState(["beasiswa"]),
  },
  data() {
    return {
      loading: true,
      snackbar: { show: false },
    };
  },
};
</script>

<style scoped>
.main {
  position: relative;
  width: 100%;
  -webkit-overflow-scrolling: touch;
}
.container {
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  flex-wrap: nowrap;
  width: auto;
  overflow-x: scroll;
  scrollbar-width: thin;
}
.item {
  pointer-events: none;
  flex: 0 0 auto;
  width: 100vw;
  height: 100vh;
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}
@media (min-width: 768px) {
  .container {
    max-width: none !important;
  }
}
::-webkit-scrollbar {
  width: 0px;
}
a {
  text-decoration: none !important;
}

/* Handle on hover */
</style>
