<template>
  <v-app id="inspire">
    <!-- :src="require('../drawer-bg.jpg')" -->
    <v-navigation-drawer
      :src="'/images/drawer-bg.jpg'"
      v-model="drawer"
      app
      clipped
      :floating="true"
      :permanent="windowWidth <= 600 ? false : permanent"
      :expand-on-hover="windowWidth <= 600 ? false : expandOnHover"
      :mini-variant="windowWidth <=600 ? false : miniVariant"
      dark
    >
      <!-- :floating="true"
      :permanent="permanent"
      :expand-on-hover="expandOnHover"
      :mini-variant="miniVariant"-->
      <v-card
        v-if="windowWidth <= 600"
        class="d-flex justify-center pt-4 pr-2 pl-2"
        flat
        tile
      >
        <v-img
          max-width="70"
          :src="'/images/LogoUIN.png'"
        ></v-img>
        <v-card-text>Aplikasi Beasiswa UIN Suska Riau</v-card-text>
      </v-card>
      <v-card
        v-if="windowWidth<=600"
        class="d-flex justify-center pr-2 pl-2"
        flat
        tile
      >
        <v-card-text>
          Selamat datang {{$route.params.petugas}}
          <br />
          <v-btn
            class="mt-2"
            outlined
            color="green darken-2"
            small
            block
            @click="logout"
          >
            <v-icon>mdi-logout-variant</v-icon>keluar
          </v-btn>
        </v-card-text>
      </v-card>
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
      <v-app-bar-nav-icon @click.stop="toggleDrawer(windowWidth<=600)"></v-app-bar-nav-icon>
      <div style="width:100%; -webkit-app-region: drag;">
        <v-toolbar-title>
          <span
            v-if="!$vuetify.breakpoint.mobile"
            class="font-weight-bold ml-4"
          >App Beasiswa</span>
          <!-- Change this automaticly later usig VUEX -->
          <span>{{$route.name}}</span>
        </v-toolbar-title>
      </div>
      <v-slide-y-transition>
        <v-btn
          v-if="checkRoute('LPJ')"
          small
          class="green darken-3"
          @click="toggleBeasiswa()"
        >
          <v-icon>mdi-plus</v-icon>tambah LPJ
        </v-btn>
      </v-slide-y-transition>
      <v-slide-y-transition>
        <v-btn
          v-if="checkRoute('Beasiswa Ongoing/Selesai')"
          small
          class="green darken-3"
          @click="toggleBeasiswa()"
        >
          <v-icon>mdi-plus</v-icon>tambah beasiswa selesai
        </v-btn>
      </v-slide-y-transition>
      <v-slide-y-transition>
        <v-btn
          v-if="checkRoute('Beasiswa')"
          small
          class="green darken-3"
          @click="toggleBeasiswa()"
        >
          <v-icon>mdi-plus</v-icon>tambah beasiswa
        </v-btn>
      </v-slide-y-transition>
      <v-slide-y-transition>
        <v-btn
          v-if="checkRoute('Instansi')"
          small
          class="green darken-3"
          @click="toggleBeasiswa()"
        >
          <v-icon>mdi-plus</v-icon>tambah instansi
        </v-btn>
      </v-slide-y-transition>
      <v-slide-y-transition>
        <v-btn
          v-if="checkRoute('Kelola Mahasiswa')"
          small
          class="green darken-3"
          @click="toggleBeasiswa()"
        >
          <v-icon>mdi-plus</v-icon>tambah mahasiswa
        </v-btn>
      </v-slide-y-transition>
      <v-slide-y-transition>
        <v-btn
          v-if="checkRoute('Akun Petugas')"
          small
          class="green darken-3"
          @click="toggleBeasiswa()"
        >
          <v-icon>mdi-plus</v-icon>tambah akun petugas
        </v-btn>
      </v-slide-y-transition>
      <v-btn
        v-if="windowWidth>=600"
        small
        text
        @click="logout"
      >
        <v-icon>mdi-logout-variant</v-icon>keluar
      </v-btn>
    </v-app-bar>

    <v-main class="bg-pattern">
      <transition
        name="slide-fade"
        mode="out-in"
      >
        <router-view></router-view>
      </transition>

      <!-- <v-fade-transition mode="in" hide-on-leave="true">
        <router-view></router-view>
      </v-fade-transition>-->
    </v-main>
  </v-app>
</template>

<script>
import { mapMutations, mapState } from "vuex";
export default {
  methods: {
    ...mapMutations(["toggleOpenBeasiswa"]),
    toggleDrawer(bool) {
      if (!bool) {
        this.miniVariant = !this.miniVariant;
        this.expandOnHover = !this.expandOnHover;
      } else {
        this.drawer = !this.drawer;
      }
    },
    toggleBeasiswa() {
      this.toggleOpenBeasiswa(true);
    },
    checkRoute(name) {
      return this.$route.name == name;
    },
    logout() {
      axios
        .get("/api/logout-petugas", {
          params: {
            user: window.localStorage.getItem("user"),
          },
        })
        .then((response) => {
          this.$store.state.auth.isAuth = false;
          console.log(this.$store.state.auth.isAuth);

          this.$router.push({ name: "Login Petugas" });
        })
        .catch(() => {
          console.log(this.$store.state.auth.isAuth + " dar catch");
          this.$store.state.auth.isAuth = false;
          this.$router.push({ path: "/login" });
        });
    },
  },
  props: {
    source: String,
  },
  computed: {
    ...mapState(["isOpenBeasiswa"]),
    nama() {
      return this.$store.state.name;
    },
    isBeasiswa() {
      if (this.$route.name == "Beasiswa") {
        return true;
      } else {
        false;
      }
    },
    isInstansi() {
      if (this.$route.name == "Instansi") {
        return true;
      } else {
        false;
      }
    },
    isKelolaMahasiswa() {
      if (this.$route.name == "Kelola Mahasiswa") {
        return true;
      } else {
        false;
      }
    },
    isAkunPetugas() {
      if (this.$route.name == "Akun Petugas") {
        return true;
      } else {
        false;
      }
    },
    pages() {
      let petugas = this.$route.params.petugas;
      return [
        {
          icon: "mdi-view-dashboard",
          title: "Dashboard",
          to: `/admin/${petugas}/dashboard`,
        },
        {
          icon: "mdi-school",
          title: "Beasiswa",
          to: `/admin/${petugas}/beasiswa`,
        },
        {
          icon: "mdi-clipboard-check-multiple",
          title: "Kelulusan",
          to: `/admin/${petugas}/kelulusan`,
        },
        // {
        //   icon: "mdi-clipboard-check-multiple",
        //   title: "Cek Berkas Permohonan",
        //   to: `/admin/${petugas}/cek-berkas-permohonan`
        // },
        {
          icon: "mdi-account-details",
          title: "Beasiswa Ongoing/Selesai",
          to: `/admin/${petugas}/permohonan`,
        },
        {
          icon: "mdi-book-multiple",
          title: "LPJ",
          to: `/admin/${petugas}/lpj`,
        },
        {
          icon: "mdi-account-group",
          title: "Petugas",
          to: `/admin/${petugas}/petugas`,
        },
        {
          icon: "mdi-office-building",
          title: "Instansi",
          to: `/admin/${petugas}/instansi`,
        },
        {
          icon: "mdi-account-supervisor-circle",
          title: "Mahasiswa",
          to: `/admin/${petugas}/mahasiswa`,
        },
        {
          icon: "mdi-file-document",
          title: "Laporan Beasiswa",
          to: `/admin/${petugas}/Laporan`,
        },
        {
          icon: "mdi-file-document-outline",
          title: "Laporan LPJ",
          to: `/admin/${petugas}/laporan-lpj`,
        },
        {
          icon: "mdi-hammer-wrench",
          title: "Pengaturan",
          to: `/admin/${petugas}/pengaturan`,
        },
      ];
    },
  },
  data: () => ({
    drawer: false,
    permanent: true,
    miniVariant: true,
    expandOnHover: true,
  }),
  mounted() {
    console.log(this.$route);
    console.log(this.$route.matched);
    // this.$vuetify.theme.dark = true;
  },
};
</script>

<style scoped>
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
