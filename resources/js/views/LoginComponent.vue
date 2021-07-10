<template>
  <v-card elevation="14">
    <v-card-title>
      Login
    </v-card-title>
    <v-card-text>
      <p class="red--text">{{error}}</p>
      <v-text-field
        prepend-inner-icon="mdi-account-circle"
        color="white"
        v-model="nim"
        label="Username"
      ></v-text-field>
      <v-text-field
        prepend-inner-icon="mdi-lock"
        color="white"
        v-model="pass"
        :type="show1 ? 'text' : 'password'"
        @keyup.enter="loginServer()"
        label="Password"
        @click:append="show1 = !show1"
        :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
      ></v-text-field>

      <v-btn
        color="green"
        :loading="loading"
        @click="(isServer)?loginServer():login()"
      >login</v-btn>

    </v-card-text>
  </v-card>

</template>

<script>
import axios from "axios";
axios.defaults.withCredentials = true;
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
import { mapMutations } from "vuex";
export default {
  data() {
    return {
      nim: "",
      pass: "",
      error: "",
      loading: false,
      isServer: true,
      show1: false,
    };
  },
  methods: {
    ...mapMutations(["mutateNim", "mutateUser"]),
    login() {
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/authenticate", {
            nim: this.nim,
            password: this.pass,
          })
          .then((result) => {
            window.localStorage.setItem("user", result.data.role);
            console.log(result);
            this.mutateNim(this.nim);
            if (result.data.status == "Authenticated") {
              this.$store.state.auth.isAuth = true;
              this.$router.push({ path: `/mahasiswa/home` });
            } else {
              this.error = "Invalid username/password";
              this.loading = false;
            }
          });
      });
    },
    loginServer() {
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/login-server", {
            username: this.nim,
            password: this.pass,
          })
          .then((response) => {
            // console.log(response);
            window.localStorage.setItem("user", response.data.role);
            // console.log(response);
            this.mutateNim(this.nim);
            if (response.data.status == "Authenticated") {
              this.$store.state.auth.isAuth = true;
              window.localStorage.setItem(
                "userDetail",
                JSON.stringify(response.data.user)
              );
              // this.mutateUser(response.data.user);
              this.$router.push({ path: `/mahasiswa/home` });
            } else {
              this.error = "Invalid username/password";
              this.loading = false;
            }

            // below is the old code

            // axios
            //   .post("/api/authenticate", {
            //     nim: this.nim,
            //     password: response.data.token
            //   })
            //   .then(result => {
            //     window.localStorage.setItem("user", result.data.role);
            //     // console.log(result);
            //     this.mutateNim(this.nim);
            //     if (result.data.status == "Authenticated") {
            //       this.$store.state.auth.isAuth = true;
            //     window.localStorage.setItem("userDetail", JSON.stringify(result.data.user));
            //       // this.mutateUser(result.data.user);
            //       this.$router.push({ path: `/mahasiswa/home` });
            //     } else {
            //       this.error = "Invalid username/password";
            //       this.loading = false;
            //     }
            //   });
          })
          .catch((error) => {
            this.error = "Terjadi kesalahan, cobalah dalam beberapa saat lagi.";
            this.loading = false;
            console.log(error);
          });
      });
    },
  },
  created() {
    axios
      .get("/api/user/mahasiswa")
      .then((response) => {
        console.log(response.data);
        console.log("go");
        this.$router.push({ path: `/mahasiswa/home` });
      })
      .catch((error) => {
        console.log(error.response.status);
        // this.$router.push("login");
      });
  },
};
</script>

<style scoped>
.bg-pattern {
  /* background-color: #f9f9f9; */
  /* background: url("../assets/pattern2.svg") repeat; */
  background: url("/pattern.svg") repeat;
  background-size: 400px;
}
</style>