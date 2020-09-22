<template>
  <v-app id="inspire">
    <v-main class="bg-pattern">
      <v-container
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="1"
            md="1"
          >
            <v-progress-circular
              style="margin:auto"
              :size="50"
              color="amber"
              indeterminate
            ></v-progress-circular>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  created() {
    if (!window.localStorage.getItem("user")) {
      this.$router.push("login");
    }
    var base_url = "http://beasiswa.test/";
    var check_url = "";
    var login_url = "";
    axios.get(base_url + "sanctum/csrf-cookie").then(response => {
      axios
        .get(base_url + "api/check", {
          params: {
            user: window.localStorage.getItem("user")
          }
        })
        .then(response => {
          var temp = response.data["user_url"].split("\\");
          var check_url = temp.join("/");
          console.log(check_url);
          var login_url = response.data["login_url"];
          axios
            .get(base_url + check_url)
            .then(response => {
              console.log(response.data);
              this.$router.push({
                path: `/${response.data["name"]}/dashboard`
              });
            })
            .catch(error => {
              console.log(error.response.status);
              this.$router.push(login_url);
            });
        });
    });
  }
};
</script>
