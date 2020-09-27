import Vue from 'vue'
import Vuex from 'vuex'
import Axios from 'axios'
var pack = require("../../../package.json")
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    url: pack.baseUrl,
    name:"idris",
    nim:"",
    cekBerkas:[],
    isLoading: false,
    isTableLoading: false,
    beasiswa:[],
    beasiswaSingle:{},
    instansi:[],
    isOpenBeasiswa:false,
  },
  mutations: {
    mutateCekBerkas(state,data){
      state.cekBerkas = data;
    },
    mutateNim(state,data){
      state.nim = data;
    },
    mutateLoading(state,data){
      state.isLoading = data;
    },
    mutateTableLoading(state,data){
      state.isTableLoading = data;
    },
    mutateBeasiswa(state,data){
      state.beasiswa = data;
    },
    mutateBeasiswaSingle(state,data){
      state.beasiswaSingle = data;
    },
    mutateInstansi(state,data){
      state.instansi = data;
    },
    toggleOpenBeasiswa(state,data){
      state.isOpenBeasiswa = data;
    }
  },
  actions: {
    getCekBerkas({commit,dispatch,state}){
      commit("mutateTableLoading",true);
      Axios.get(state.url+"/api/pemohon/cek-berkas").then(response=>{
        commit('mutateCekBerkas',response.data)
        commit("mutateTableLoading",false);

      })
    },
    getBeasiswaSingle({commit,dispatch,state},id){
      commit("mutateTableLoading",true);
      return new Promise((resolve,reject)=>{
        Axios.get(state.url+"/api/beasiswa/"+id).then(response=>{
          commit('mutateBeasiswaSingle',response.data)
          commit("mutateTableLoading",false);
          resolve(response.data)
        }).catch(error=>{
          reject(error)
        })
      })
     
    },
    getBeasiswa({commit,dispatch,state}){
      commit("mutateTableLoading",true);
      Axios.get(state.url+"/api/beasiswa").then(response=>{
        commit('mutateBeasiswa',response.data)
        commit("mutateTableLoading",false);

      })
    },
    storeBeasiswa({commit,dispatch,state},data){
      return new Promise((resolve,reject)=>{
        Axios.post(state.url+"/api/beasiswa",{data}).then(response=>{
          dispatch('getBeasiswa')
          resolve(response)
        }).catch(error=>{
          reject(error)
        })
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
