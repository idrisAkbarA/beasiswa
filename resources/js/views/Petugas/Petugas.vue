<template>
  <v-app id="inspire">
      <!-- :src="require('../drawer-bg.jpg')" -->
    <v-navigation-drawer
      :src="'/images/drawer-bg.jpg'"
      v-model="drawer"
      app
      clipped
      :floating="true"
      :permanent="permanent"
      :expand-on-hover="expandOnHover"
      :mini-variant="miniVariant"
      dark
    >
      <v-list dense>
        <v-list-item
          v-for="(page, i) in pages"
          :key="i"
          :to="page.to"
          :two-line="(page.subtitle)? true: false"
          router
          exact
        >
          <v-list-item-action>
            <v-icon>{{page.icon}}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{page.title}}</v-list-item-title>
            <v-list-item-subtitle v-if="page.subtitle">{{page.subtitle}}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      dense
      clipped-left
    >
      <v-app-bar-nav-icon @click.stop="miniVariant= !miniVariant; expandOnHover= !expandOnHover"></v-app-bar-nav-icon>
      <div style="width:100%; -webkit-app-region: drag;">
        <v-toolbar-title>
          <span class="font-weight-bold ml-4">
            App Beasiswa
          </span>
          <!-- Change this automaticly later usig VUEX -->
          <span> | {{$route.name}}</span>
        </v-toolbar-title>
      </div>
      <v-slide-y-transition>
      <v-btn
      v-if="isBeasiswa"
      small
      class="green darken-3"
      @click="toggleBeasiswa()"
      >
        <v-icon>mdi-plus</v-icon>tambah beasiswa
      </v-btn>
      </v-slide-y-transition>
      <v-btn
      small
        text
        @click="logout"
      >
        <v-icon>mdi-logout-variant</v-icon>keluar
      </v-btn>
    </v-app-bar>

    <v-main
      class="bg-pattern"
      
    >
      <transition
        name="slide-fade"
        mode="out-in"
      >
        <router-view></router-view>
      </transition>

      <!-- <v-fade-transition mode="in" hide-on-leave="true">
        <router-view></router-view>
      </v-fade-transition> -->
    </v-main>
  </v-app>
</template>

<script>

import {mapMutations, mapState } from "vuex";
export default {
  methods: {
    ...mapMutations(["toggleOpenBeasiswa"]),
    toggleBeasiswa(){
      console.log(this.isOpenBeasiswa)
      this.toggleOpenBeasiswa(true)
    },
    logout() {
      axios
        .get("http://beasiswa.test/api/logout",{params:{
          user: window.localStorage.getItem('user')
        }})
        .then((response) => {
          this.$router.push({name:"Login Petugas"});
        })
        .catch(() => {
          this.$router.push({ path: "/login" });
        });
    },
   
  },
  props: {
    source: String,
  },
  computed:{
    ...mapState(["isOpenBeasiswa"]),
    nama(){
      return this.$store.state.name
    },
    isBeasiswa(){
      if(this.$route.name=="Beasiswa"){
        return true;
      }else{
        false
      }
    },
    pages(){
      let petugas = this.$route.params.petugas
      return [ {
        icon: "mdi-account",
        title: "Akun",
        subtitle: `${this.$route.params.petugas}`, // change this dynamicly later
        to: `/${petugas}/account`,
      },
      {
        icon: "mdi-view-dashboard",
        title: "Dashboard",
        to: `/${petugas}/dashboard`,
      },
      {
        icon: "mdi-school",
        title: "Beasiswa",
        to: `/${petugas}/beasiswa`,
      },
      {
        icon: "mdi-account-group",
        title: "Petugas",
        to: `/${petugas}/petugas`,
      },
      ]
    }
  },
  data: () => ({
    drawer: false,
    permanent: true,
    miniVariant: true,
    expandOnHover: true,
  }),
  mounted() {
    console.log(this.$route);
    // this.$vuetify.theme.dark = true;
  },
};
</script>

<style>
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
/* The emerging W3C standard
   that is currently Firefox-only */
*::-webkit-scrollbar {
  width: 7px;

  margin: 10px;
  transition: 1s ease;
}

/* Track */

*::-webkit-scrollbar-track {
  background: transparent;
  /* background: #F9F9F9; */

  margin: 10px;
}

/* Handle */

*::-webkit-scrollbar-thumb {
  transition: 1s ease;
  background: #f79593;
  opacity: 0.5;
  border-radius: 25px;

  margin: 10px;
}

/* Handle on hover */

*::-webkit-scrollbar-thumb:hover {
  transition: 1s ease;
  background: #ff8481;
}
.bg-pattern {
  background: url("/pattern.svg") repeat;
  background-size: 400px;
}
a {
  text-decoration: none !important;
}
</style>