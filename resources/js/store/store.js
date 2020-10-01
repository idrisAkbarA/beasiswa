import Vue from 'vue'
import Vuex from 'vuex'
import Axios from 'axios'
import mhsModule from './modules/mhsModule'
var pack = require("../../../package.json")
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    url: pack.baseUrl,
    name:"idris",
    akunPetugas:[],
    nim:"",
    cekBerkas:[],
    cekInterview:[],
    cekSurvey:[],
    cekSelection:[],
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
    mutateCekInterview(state,data){
      state.cekInterview = data;
    },
    mutateCekSurvey(state,data){
      state.cekSurvey = data;
    },
    mutateCekSelection(state,data){
      state.cekSelection = data;
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
    getCekInterview({commit,dispatch,state}){
      commit("mutateTableLoading",true);
      Axios.get(state.url+"/api/pemohon/cek-interview").then(response=>{
        commit('mutateCekInterview',response.data)
        commit("mutateTableLoading",false);

      })
    },
    getCekSurvey({commit,dispatch,state}){
      commit("mutateTableLoading",true);
      Axios.get(state.url+"/api/pemohon/cek-survey").then(response=>{
        commit('mutateCekSurvey',response.data)
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
    getBeasiswaNoAuth({commit,dispatch,state}){
      return new Promise((resolve,reject)=>{
        commit("mutateTableLoading",true);
        Axios.get(state.url+"/api/beasiswa/no-auth").then(response=>{
          commit('mutateBeasiswa',response.data)
          commit("mutateTableLoading",false);
          resolve(response.data)
        })
        
      });
    },
    getBeasiswaWithPermohonan({commit,dispatch,state}){
      commit("mutateTableLoading",true);
      Axios.get(state.url+"/api/beasiswa/with-permohonan").then(response=>{
        commit('mutateBeasiswa',response.data)
        commit("mutateTableLoading",false);

      })
    },
    storeAkunPetugas({commit,dispatch,state},data){
      return new Promise((resolve,reject)=>{
        Axios.post(state.url+"/api/petugas",data).then(response=>{
          dispatch('getAkunPetugas')
          resolve(response)
        }).catch(error=>{
          reject(error)
        })
      })

    },
    editAkunPetugas({commit,dispatch,state},data){
      return new Promise((resolve,reject)=>{
        console.log(data)
      Axios.put(state.url+"/api/petugas/"+data.id,{ name: data.name, password: data.password, role: data.role}).then(response=>{
        dispatch('getAkunPetugas')
        resolve(response)
      }).catch(error=>{
        reject(error)
      })})
    },
    editBeasiswa({commit,dispatch,state},data){
      return new Promise((resolve,reject)=>{
        console.log(data+"pante")
      Axios.put(state.url+"/api/beasiswa/"+data.id,{
        id: data.id,
        nama:  data.nama,
        deskripsi:  data.deskripsi,
        kuota:  data.kuota,
        instansi:  data.id,
        fields:  data.fields,
        is_survey:  data.is_survey,
        is_wawancara:  data.is_wawancara,
        awal_wawancara: data.awal_wawancara,
        akhir_wawancara:  data.akhir_wawancara,
        awal_survey:  data.awal_survey,
        akhir_survey: data.akhir_survey,
        awal_berkas:  data.awal_berkas,
        akhir_berkas: data.akhir_berkas
      }).then(response=>{
        dispatch('getBeasiswa')
        resolve(response)
      }).catch(error=>{
        reject(error)
      })})
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
        Axios.post(state.url+"/api/instansi",data).then(response=>{
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
    deleteBeasiswa({commit,dispatch,state},id){

      Axios.delete(state.url+"/api/beasiswa/"+id).then(response=>{
        dispatch('getBeasiswa')
        console.log(response)
      })
    },
    editInstansi({commit,dispatch,state},data){
      Axios.put(state.url+"/api/instansi/"+data.id,{name:data.name}).then(response=>{
        dispatch('getInstansi')
      })
    },
  },
  modules: {
    mhs: mhsModule
  }
})
