<template>
  <v-container
    fluid
    fill-height
    class="main pa-0 ma-0"
  >
  <!-- <v-card @click="goto">pepek</v-card> -->
  
    <div class="container" id="scrollMe">

      <v-card
        @click="goto()"
        ripple
        :to="'/'+item.id"
        class="ma-3 item"
        :height="300*1.2"
        :width="200*1.2"
        v-for="(item,index) in beasiswa"
        :key="index"
      >
        <v-img 
        gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
        :src="'https://picsum.photos/200/300?random='+index">
          <!-- <v-img :src="'https://picsum.photos/200/300?grayscale&blur=1&random='+index"> -->

          <v-card-title><span>{{index+1}}</span>
            <v-spacer></v-spacer><span class="caption">Tersedia</span>
          </v-card-title>
          <v-card-text>
            <h1>{{item.nama}}</h1>
          </v-card-text>
        </v-img>
      </v-card>
    </div>
   
  </v-container>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  methods: {
    ...mapActions(["getBeasiswa"]),
    goto(){
        console.log("hello")
    },
    handleScroll(event) {
    //   var item = document.getElementsByClassName("container");
      var item = document.getElementById("scrollMe");
      if (event.deltaY > 0) item.scrollLeft += 100;
      else item.scrollLeft -= 100;
      console.log(item.scrollLeft)
    }
  },
  mounted(){
        var item = document.getElementById("scrollMe");
        item.scroll = true;
        console.log(item)
   
  },
  created() {
    this.getBeasiswa();

    window.addEventListener("wheel", this.handleScroll);
  },
  computed: {
    ...mapState(["beasiswa"])
  }
};
</script>

<style scoped>
.main {
    
  position: relative;
  width: 100%;
  -webkit-overflow-scrolling: touch;
}
.container {
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  flex-wrap: nowrap;
  width: auto;
  overflow-x: scroll;
  scrollbar-width: thin;
}
.item {
  pointer-events: none;
  flex: 0 0 auto;
  width: 100vw;
  height: 100vh;
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}
@media (min-width: 768px){
    .container{
        max-width: none !important;
    }
}
::-webkit-scrollbar {
  width: 0px;
}

/* Handle on hover */

</style>