<template>
  <v-container>
    <v-row align="center" justify="center">
      <v-card :width="width()">
        <v-card-title>
          <v-icon class="mr-2">mdi-hammer-wrench</v-icon> Pengaturan
        </v-card-title>
        <v-card-subtitle> Pengaturan aplikasi Beasiswa </v-card-subtitle>
        <v-card-text>
          <v-row no-gutters align="center">
            <v-col cols="6"> Mode maintenance </v-col>
            <v-col cols="6">
              <v-switch color="green" v-model="isMaintenanceMode"> </v-switch>
            </v-col>
          </v-row>
          <v-row no-gutters align="center">
            <v-col cols="6"> Mode maintenance untuk halaman verifikator </v-col>
            <v-col cols="6">
              <v-switch color="green" v-model="isVerificatorMaintenanceMode">
              </v-switch>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-row>
  </v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  created() {
    this.getAppSettings();
  },
  watch: {
    appSettings(v) {
      console.log(v);
    },
  },
  computed: {
    ...mapState(["appSettings"]),
    isMaintenanceMode: {
      set: function (v) {
        if (v == true) {
          this.appSettings["isMaintenanceMode"] = 1;
        } else {
          this.appSettings["isMaintenanceMode"] = 0;
        }
        this.editAppSettings(this.appSettings);
      },
      get: function () {
        return this.appSettings["isMaintenanceMode"] == 1 ? true : false;
      },
    },
    isVerificatorMaintenanceMode: {
      set: function (v) {
        if (v == true) {
          this.appSettings["isVerificatorMaintenanceMode"] = 1;
        } else {
          this.appSettings["isVerificatorMaintenanceMode"] = 0;
        }
        this.editAppSettings(this.appSettings);
      },
      get: function () {
        return this.appSettings["isMaintenanceMode"] == 1 ? true : false;
      },
    },
  },
  methods: {
    ...mapActions(["getAppSettings", "editAppSettings"]),
    width() {
      if (this.windowWidth <= 600) {
        return "70%";
      } else if (this.windowWidth <= 960) {
        return "50%";
      } else {
        return "60%";
      }
    },
  },
  data() {
    return {};
  },
};
</script>

<style>
</style>
