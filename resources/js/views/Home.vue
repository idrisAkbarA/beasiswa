<template>
  <component :is="component"></component>
</template>

<script>
import { mapState } from "vuex";
import Loading from "./Loading";
import FrontPage from "./FrontPage";
import Maintenance from "./Maintenance";
export default {
  components: {
    Loading,
    FrontPage,
    Maintenance
  },
  created() {
    this.checkMaintenance();
    // axios
    //   .get("/api/user")
    //   .then(response => {
    //     console.log(response.data);
    //     console.log("go");
    //     this.$router.push({ path: `/mahasiswa/home` });
    //   })
    //   .catch(error => {
    //     this.isAuthenticated = false;
    //     this.component = "FrontPage";
    //     // this.$router.push("login");
    //   });
  },
  computed: {
    ...mapState(["url"])
  },
  methods: {
    checkMaintenance(){
       axios.get("/api/beasiswa/settings").then(response => {
         console.log(response.data['isMaintenanceMode'])
              if(response.data['isMaintenanceMode'] == 0){
                console.log("maintenance")
                this.checkLogin()
              }else{
                this.component = "Maintenance"
              }
            }).catch(err=>{
              this.checkMaintenance()
            });
    },
    checkLogin(){
      axios
      .get("/api/user")
      .then(response => {
        console.log(response.data);
        console.log("go");
        this.$router.push({ path: `/mahasiswa/home` });
      })
      .catch(error => {
        this.isAuthenticated = false;
        this.component = "FrontPage";
        // this.$router.push("login");
      });
    }
  },
  data() {
    return {
      component: "Loading",
      isAuthenticated: null
    };
  }
};
</script>
