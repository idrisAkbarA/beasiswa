import Vue from 'vue'
import Vuex from 'vuex'
import Axios from 'axios'
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    url:"http://beasiswa.test",
    name:"idris",
    beasiswa:[],
    instansi:[],
    isOpenBeasiswa:false,
  },
  mutations: {
    mutateBeasiswa(state,data){
      state.beasiswa = data;
    },
    mutateInstansi(state,data){
      state.instansi = data;
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
    storeBeasiswa({commit,dispatch,state},data){
      Axios.post(state.url+"/api/beasiswa",{data}).then(response=>{
        commit('mutateBeasiswa',response.data)
      })
    },
    getInstansi({commit,dispatch,state}){
      Axios.get(state.url+"/api/instansi").then(response=>{
        commit('mutateInstansi',response.data)
      })
    },
  },
  modules: {
  }
})
