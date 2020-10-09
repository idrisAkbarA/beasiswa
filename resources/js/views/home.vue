<template>
  <component :is="component"></component>
</template>

<script>

import { mapState} from 'vuex';
import Loading from './Loading';
import FrontPage from './FrontPage';
export default {
  components:{
    Loading,FrontPage
  },
  created() {
    if (!window.localStorage.getItem("user")) {
      this.$router.push("login");
    }
    var base_url = this.url+"/";
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
              // this.$router.push(login_url);
              this.isAuthenticated = false
              this.component = "FrontPage"
            });
        });
    });
  },
  computed:{
    ...mapState(['url'])
  },
  data() {
    return {
      component:"Loading",
      isAuthenticated:null,
    }
  },
};
</script>
