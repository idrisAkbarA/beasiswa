<template>
  <v-app>
    <v-navigation-drawer
      style="z-index:11"
      :src="'/images/drawer-bg.jpg'"
      v-model="drawer"
      app
    >
      <v-card
        class="d-flex justify-center pt-4 pr-2 pl-2 pb-4"
        flat
        tile
      >
        <v-img
          max-width="70"
          :src="'/images/LogoUIN.png'"
        >
        </v-img>
        <v-card-text> Aplikasi Beasiswa UIN Suska Riau</v-card-text>
      </v-card>
      <v-card
        class="d-flex justify-center  pr-2 pl-2  mb-5"
        flat
        tile
      >
        <v-card-text> {{user.nama}} <br> <span class="caption">{{user.nim}}</span></v-card-text>
      </v-card>
      <v-row>

        <p class="subtitle ml-8 mr-10">
          Selamat datang di aplikasi Beasiswa UIN Suska Riau
        </p>

      </v-row>
      <v-list dense>
        <!-- :to="page.to" -->
        <v-list-item
          v-if="$route.name!='List Beasiswa'"
          exact
          @click="goback"
        >
          <v-list-item-action>
            <v-icon>mdi-keyboard-backspace</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>kembali</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item
          router
          exact
          @click="goToPermohonan"
        >
          <v-list-item-action>
            <v-icon>mdi-text</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Permohonan Saya</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      dense
      style="z-index:10"
    >
      <v-app-bar-nav-icon
        class="hidden-lg-and-up"
        @click.stop="drawer = !drawer"
      ></v-app-bar-nav-icon>
      <div style="width:100%; -webkit-app-region: drag;">
        <v-toolbar-title>
          <router-link to="/mahasiswa/home">
            <span class="font-weight-bold ml-4 white--text">
              App Beasiswa
            </span>
          </router-link>
          <!-- Change this automaticly later usig VUEX -->
          <!-- <span> | {{$route.name}}</span> -->
        </v-toolbar-title>
      </div>
      <v-btn
        small
        text
        @click="logout"
      >
        <v-icon>mdi-logout-variant</v-icon>keluar
      </v-btn>
    </v-app-bar>

    <!-- Sizes your content based upon application components -->
    <v-main class="bg-pattern">

      <!-- Provides the application the proper gutter -->
      <v-container fluid>

        <!-- If using vue-router -->
        <transition
          name="slide-fade"
          mode="out-in"
        >
          <router-view></router-view>
        </transition>
      </v-container>

    </v-main>
  </v-app>
</template>

<script>
import { mapState } from "vuex";
export default {
  computed: {
    ...mapState(["url"]),
    user() {
      return JSON.parse(window.localStorage.getItem("userDetail"));
    }
  },
  created() {
    console.log(this.user);
  },
  methods: {
    goback() {
      this.$router.push({ name: "List Beasiswa" });
    },
    goToPermohonan() {
      this.$router.push({ name: "Permohonan Saya" });
    },
    logout() {
      this.$store.state.auth.isAuth = false;
      axios
        .get("/api/logout", {
          params: {
            user: window.localStorage.getItem("user")
          }
        })
        .then(response => {
          this.$router.push({ name: "Landing Page" });
        })
        .catch(() => {
          this.$router.push({ name: "Landing Page" });
        });
    }
  },
  data() {
    return {
      drawer: null
    };
  }
};
</script>

<style scoped>
.bg-pattern {
  background: url("/pattern.svg") repeat;
  background-size: 400px;
}
a {
  text-decoration: none !important;
}
.slide-fade-enter-active {
  transition: all 0.2s ease-out;
}
.slide-fade-leave-active {
  transition: all 0.2s ease-in;
}
.slide-fade-enter
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: scale(1.1);

  opacity: 0;
}
.slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: scale(0.9);

  opacity: 0;
}
</style>