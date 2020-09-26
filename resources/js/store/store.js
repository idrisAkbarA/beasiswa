import Vue from 'vue'
import Vuex from 'vuex'
import Axios from 'axios'
var pack = require("../../../package.json")
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    url: pack.baseUrl,
    name:"idris",
    isLoading: false,
    isTableLoading: false,
    beasiswa:[],
    instansi:[],
    isOpenBeasiswa:false,
  },
  mutations: {
    mutateLoading(state,data){
      state.isLoading = data;
    },
    mutateTableLoading(state,data){
      state.isTableLoading = data;
    },
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
      commit("mutateTableLoading",true);
      Axios.get(state.url+"/api/beasiswa").then(response=>{
        commit('mutateBeasiswa',response.data)
        commit("mutateTableLoading",false);

      })
    },
    storeBeasiswa({commit,dispatch,state},data){
      Axios.post(state.url+"/api/beasiswa",{data}).then(response=>{
        dispatch('getBeasiswa')
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
