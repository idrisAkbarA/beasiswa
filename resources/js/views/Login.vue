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
            <v-card elevation="14">
              <v-card-title>
                Login
              </v-card-title>
              <v-card-text>
                <v-text-field v-model="nim" label="Username"></v-text-field>
                <v-text-field v-model="pass" type="password" @keyup.enter="login" label="Password"></v-text-field>

                <v-btn @click="login">login</v-btn>

              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>

</template>

<script>
import axios from 'axios'
import {mapMutations} from 'vuex'
export default {
  data() {
    return {
      nim: "",
      pass: "",
    };
  },
  methods:{
    ...mapMutations(['mutateNim']),
      login(){
        axios.get("http://beasiswa.test/sanctum/csrf-cookie").then(response=>{
          console.log(response)
          axios.post("http://beasiswa.test/api/authenticate",{
              'nim': this.nim,
              'password': this.pass
          }).then(response=>{
            console.log(response)
            this.mutateNim(this.nim);
            this.$router.push({ path: `/mahasiswa/home` })
            // this.$router.push({ path: `/${response.data.user.nama}/dashboard` })
          })
        })
      }
  },
  created() {
    axios.get("http://beasiswa.test/sanctum/csrf-cookie").then((response) => {
      console.log(response)

      axios
        .get("http://beasiswa.test/api/user")
        .then((response) => {
          console.log(response.data);
          // this.$router.push({ path: `/${response.data['name']}/dashboard` })
        })
        .catch((error) => {
          console.log(error.response.status);
          // this.$router.push("login");
        });
    });
  },
};
</script>

<style scoped>
.bg-pattern {
  /* background-color: #f9f9f9; */
  /* background: url("../assets/pattern2.svg") repeat; */
  background-size: 400px;
}
</style>