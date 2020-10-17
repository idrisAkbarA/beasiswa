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
          <v-sheet
            tile
            color="transparent"
            :width="width()"
          >
            <LoginComponent></LoginComponent>
          </v-sheet>

        </v-row>
      </v-container>
    </v-main>
  </v-app>

</template>

<script>
import LoginComponent from "./LoginComponent";
import axios from "axios";
axios.defaults.withCredentials = true;
import { mapMutations } from "vuex";
export default {
  components: {
    LoginComponent
  },
 
  methods: {
    width() {
      // console.log(this.windowWidth)
      if (this.windowWidth <= 600) {
        return "70%";
      } else if (this.windowWidth <= 960) {
        return "30%";
      } else {
        return "20%";
      }
    },
    
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