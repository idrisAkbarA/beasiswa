import Vue from 'vue'
import Vuex from 'vuex'
import Axios from 'axios'
var pack = require("../../../package.json")
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    url: pack.baseUrl,
    name:"idris",
    akunPetugas:[],
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
    mutateAkunPetugas(state,data){
      state.akunPetugas = data;
    },
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
    getAkunPetugas({commit,dispatch,state}){
      commit("mutateTableLoading",true);
      Axios.get(state.url+"/api/petugas").then(response=>{
        commit('mutateAkunPetugas',response.data)
        commit("mutateTableLoading",false);

      })
    },
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
    storeInstansi({commit,dispatch,state},data){
      return new Promise((resolve,reject)=>{
        Axios.post(state.url+"/api/instansi",{data}).then(response=>{
          dispatch('getInstansi')
          resolve(response)
        }).catch(error=>{
          reject(error)
        })
      })
    },
    getInstansi({commit,dispatch,state}){
      commit("mutateTableLoading",true);
      Axios.get(state.url+"/api/instansi").then(response=>{
        commit('mutateInstansi',response.data)
        commit("mutateTableLoading",false);
      })
    },
    deleteInstansi({commit,dispatch,state},id){
      Axios.delete(state.url+"/api/instansi/"+id).then(response=>{
        dispatch('getInstansi')
      })
    },
    editInstansi({commit,dispatch,state},id,data){
      Axios.put(state.url+"/api/instansi/"+id).then(response=>{
        dispatch('getInstansi')
      })
    },
  },
  modules: {
  }
})
