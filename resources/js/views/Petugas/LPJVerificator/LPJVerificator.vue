<template>
  <v-app>
    <v-main class="bg">
      <v-app-bar
        app
        flat
        elevate-on-scroll
        style="background:rgba(56, 203, 157, 1) 100%"
      >
        <v-avatar :tile="true">
          <img
            :src="'/images/LogoUIN.png'"
            alt="logo"
          >
        </v-avatar>
        <div style="width:100%; -webkit-app-region: drag;">
          <v-toolbar-title>
            <v-container>
              <v-row no-gutters>
                <v-col cols="12">
                  <span v-if="windowWidth >= 600" class="font-weight-bold ml-4">
                    <router-link
                      :to="'Home'"
                      class="text-white"
                    >
                      Beasiswa
                    </router-link>
                  </span>
                  <span v-if="windowWidth >= 600"> | Verifikator</span>
                </v-col>
              </v-row>
            </v-container>
            <!-- Change this automaticly later usig VUEX -->
          </v-toolbar-title>
        </div>
        <v-btn
          small
          text
          @click="laporan"
        >
          <v-icon>mdi-file-document</v-icon>Laporan
        </v-btn>
        <v-btn
          small
          text
          @click="history"
        >
          <v-icon>mdi-history</v-icon>History
        </v-btn>
        <v-btn
          small
          text
          @click="logout"
        >
          <v-icon>mdi-logout-variant</v-icon>keluar
        </v-btn>
      </v-app-bar>
      <v-container
        fluid
        fill-height
      >
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import { mapState } from "vuex";
export default {
  created() {
    // this.checkMaintenance()
  },
  methods: {
    checkMaintenance() {
      axios
        .get("/api/beasiswa/settings")
        .then(response => {
          console.log(response.data["isVerificatorMaintenanceMode"]);
          if (response.data["isVerificatorMaintenanceMode"] == 1) {
            console.log("maintenance");
          } else {
            console.log("not maintenance");
          }
        })
        .catch(err => {
          this.checkMaintenance();
        });
    },
    history() {
      this.$router.push({ name: "Verificator History" });
    },
    laporan() {
      this.$router.push({ name: "Verificator Report" });
    },
    logout() {
      axios
        .get("/api/logout-petugas", {
          params: {
            user: window.localStorage.getItem("user")
          }
        })
        .then(response => {
          this.$store.state.auth.isAuth = false;
          console.log(this.$store.state.auth.isAuth);

          this.$router.push({ name: "Login Petugas" });
        })
        .catch(() => {
          console.log(this.$store.state.auth.isAuth + " dar catch");
          this.$store.state.auth.isAuth = false;
          this.$router.push({ path: "/login" });
        });
    }
  }
};
</script>

<style>
.bg {
  background: rgb(9, 8, 30);
  background: linear-gradient(
    0deg,
    rgba(9, 8, 30, 1) 0%,
    rgba(16, 16, 27, 1) 42%,
    rgba(20, 21, 39, 1) 61%,
    rgba(56, 203, 157, 1) 61%,
    rgba(56, 203, 157, 1) 100%
  );
}
</style>
