<template>
  <v-app id="inspire">
    <v-main class="bg-pattern">
      <v-container
        class="fill-height "
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="4"
            xs="6"
            sm="6"
            md="4"
            lg="3"
          >
            <LoginComponent></LoginComponent>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>

</template>

<script>
import LoginComponent from './LoginComponent';
import axios from "axios";
axios.defaults.withCredentials = true;
import { mapMutations } from "vuex";
export default {
  components:{
    LoginComponent
  },
  data() {
    return {
      nim: "",
      pass: "",
      error: "",
      loading: false,
      isServer: true,
    };
  },
  methods: {
    ...mapMutations(["mutateNim"]),
    login() {
      this.loading = true;
      axios.get(this.url +"/sanctum/csrf-cookie").then(response => {
        axios
          .post(this.url +"/api/authenticate", {
            nim: this.nim,
            password: this.pass
          })
          .then(result => {
            window.localStorage.setItem("user", result.data.role);
            console.log(result);
            this.mutateNim(this.nim);
            if (result.data.status == "Authenticated") {
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
      axios.get(this.url +"/sanctum/csrf-cookie").then(response => {
        axios
          .post(this.url +"/api/login-server", {
            username: this.nim,
            password: this.pass
          })
          .then(response => {
            console.log(response)
            axios
              .post(this.url +"/api/authenticate", {
                nim: this.nim,
                password: response.data.token
              })
              .then(result => {
                window.localStorage.setItem("user", result.data.role);
                console.log(result);
                this.mutateNim(this.nim);
                if (result.data.status == "Authenticated") {
                  this.$router.push({ path: `/mahasiswa/home` });
                } else {
                  this.error = "Invalid username/password";
                  this.loading = false;
                }
              });
          })
          .catch(error => {
            this.loading = false;
            console.log(error);
          });
      });
    },
  },
  created() {
    axios
      .get(this.url +"/api/user")
      .then(response => {
        console.log(response.data);
        console.log("go");
        this.$router.push({ path: `/mahasiswa/home` });
      })
      .catch(error => {
        console.log(error.response.status);
        // this.$router.push("login");
      });
  }
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