import Vue from 'vue'
import Vuex from 'vuex'
import Axios from 'axios'
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    url:"http://beasiswa.test",
    name:"idris",
    beasiswa:[],
    isOpenBeasiswa:false,
  },
  mutations: {
    mutateBeasiswa(state,data){
      state.beasiswa = data;
    },
    toggleOpenBeasiswa(state,data){
      state.isOpenBeasiswa = data;
    }
  },
  actions: {
    getBeasiswa({commit,dispatch,state}){
      Axios.get(state.url+"/api/beasiswa").then(response=>{
        commit('mutateBeasiswa',response.data)
      })
    },
  },
  modules: {
  }
})
