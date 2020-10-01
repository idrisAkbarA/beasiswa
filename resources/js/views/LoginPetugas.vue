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
            cols="8"
            sm="8"
            md="4"
            lg="3"
          >
            <v-card elevation="14">
              <v-card-title>
                Login Petugas
              </v-card-title>
              <v-card-text>
                <p class="red--text">{{error}}</p>
                <v-text-field
                color="white"
                  v-model="name"
                  label="Username"
                ></v-text-field>
                <v-text-field
                color="white"
                  v-model="pass"
                  type="password"
                  @keyup.enter="login"
                  label="Password"
                ></v-text-field>

                <v-btn color="green" :loading="loading" @click="login">login</v-btn>

              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>

</template>

<script>
import axios from "axios";
axios.defaults.withCredentials = true;
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
export default {
  data() {
    return {
      name: "",
      pass: "",
      error: "",
      loading:false,
    };
  },
  methods: {
    login() {
      this.loading = true;
      axios.get("http://beasiswa.test/sanctum/csrf-cookie").then(response => {
        // console.log(response)
        axios
          .post("http://beasiswa.test/api/authenticate/petugas", {
            name: this.name,
            password: this.pass
          })
          .then(response => {
            window.localStorage.setItem("user", response.data.role);
            console.log(response.data);
            if (response.data.status == "Authenticated") {
              if (response.data.user.role == 1) {
                this.$router.push({
                  path: `/${response.data.user.name}/dashboard`
                });
              } else if (response.data.user.role == 2) {
                console.log("interviewer");
                this.$router.push({
                  path: `/interviewer/${response.data.user.name}/home`
                });
              } else if (response.data.user.role == 3) {
                console.log("interviewer");
                this.$router.push({
                  path: `/surveyor/${response.data.user.name}/home`
                });
              }
              else if (response.data.user.role == 4) {
                console.log("interviewer");
                this.$router.push({
                    path: `/petinggi/${response.data.user.name}/home`
                });
              }else if(response.data.user.role == 5){
                this.$router.push({
                    path: `/verificator/${response.data.user.name}/home`
                });
              }else if(response.data.user.role == 0){
                console.log("asd");
                this.$router.push({
                    path: `/super/${response.data.user.name}`
                });
              }
            } else {
              this.error = "Invalid username/password";
              this.loading = false;
            }
          });
      });
    }
  },
  created() {
    axios.get("http://beasiswa.test/sanctum/csrf-cookie").then(response => {
      // console.log(response)

      axios
        .get("http://beasiswa.test/api/user/petugas")
        .then(response => {
          console.log(response.data);
          this.$router.push({ path: `/${response.data["name"]}/dashboard` });
        })
        .catch(error => {
          console.log(error.response.status);
          // this.$router.push("login");
        });
    });
  }
};
</script>

<style scoped>
.bg-pattern {
  /* background-color: #f9f9f9; */
  background: url("/pattern.svg") repeat;
  background-size: 400px;
}
</style>
