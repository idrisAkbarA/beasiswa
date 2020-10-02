<template>
  <v-app>
    <v-navigation-drawer
      :src="'/images/drawer-bg.jpg'"
      v-model="drawer"
      app
    >
      <v-card
        class="d-flex justify-center pt-4 pr-2 pl-2 pb-4 mb-5"
        flat
        tile
      >
        <v-img
          max-width="70"
          :src="'/images/logoUIN.png'"
        >
        </v-img>
        <v-card-text> Aplikasi Beasiswa UIN Suska Riau</v-card-text>
      </v-card>
      <v-row>
        <p class="subtitle ml-8 mr-10">
          Selamat datang di aplikasi Beasiswa UIN Suska Riau
        </p>

      </v-row>
      <v-list dense>
        <!-- :to="page.to" -->
        <v-list-item v-if="$route.name!='List Beasiswa'"
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
          <span class="font-weight-bold ml-4">
            App Beasiswa
          </span>
          <!-- Change this automaticly later usig VUEX -->
          <span> | {{$route.name}}</span>
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
        <router-view></router-view>
      </v-container>
     
    </v-main>
  </v-app>
</template>

<script>
export default {
    created(){

    },
  methods: {
    goback(){
      this.$router.push({name:"List Beasiswa"})
    },
    goToPermohonan(){
      this.$router.push({name:"Permohonan Saya"})
    },
    logout() {
      axios
        .get("http://beasiswa.test/api/logout", {
          params: {
            user: window.localStorage.getItem("user")
          }
        })
        .then(response => {
          this.$router.push({ name: "Login" });
        })
        .catch(() => {
          this.$router.push({ path: "/login" });
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
</style>