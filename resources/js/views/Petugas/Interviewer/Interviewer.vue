<template>
  <v-app>

    <!-- Sizes your content based upon application components -->

    <v-main class="bg">
      <v-app-bar
        app
        flat
        color="transparent"
        hide-on-scroll
      >
        <v-avatar :tile="true">
          <img
            :src="'/images/LogoUIN.png'"
            alt="logo"
          >
        </v-avatar>
        <div style="width:100%; -webkit-app-region: drag;">
          <v-toolbar-title>
            <span class="font-weight-bold ml-4">
              <router-link
                :to="'Home'"
                class="text-white"
              >
                App Beasiswa
              </router-link>
            </span>
            <!-- Change this automaticly later usig VUEX -->
            <span> | Wawancara</span>
          </v-toolbar-title>
        </div>
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
        <!-- -->
      </v-app-bar>
      <!-- Provides the application the proper gutter -->
      <v-container
        fluid
        fill-height
      >

        <!-- If using vue-router -->
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import { mapState } from "vuex";
export default {
  methods: {
    history() {
      this.$router.push({ name: "Interviewer History" });
    },
    logout() {
      axios
        .get("/api/logout-petugas", {
          params: {
            user: window.localStorage.getItem("user")
          }
        })
        .then(response => {
          this.$router.push({ name: "Login Petugas" });
        })
        .catch(() => {
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
