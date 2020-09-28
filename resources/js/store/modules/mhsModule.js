import Axios from 'axios'

export default{
    state: () => ({
        mahasiswa: []
    }),
    mutations: {
        mutateMahasiswa(state, data){
            state.mahasiswa = data;
        }
     },
    actions: {
        getMahasiswa({commit,dispatch,rootState}){
            commit("mutateTableLoading",true);
            Axios.get(rootState.url+"/api/user").then(response=>{
              commit('mutateMahasiswa',response.data)
              commit("mutateTableLoading",false);
            })
        },
        storeMahasiswa({commit,dispatch,rootState},data){
            return new Promise((resolve,reject)=>{
              Axios.post(rootState.url+"/api/user",data).then(response=>{
                dispatch('getMahasiswa')
                resolve(response)
              }).catch(error=>{
                reject(error)
              })
            })
        },
        editMahasiswa({commit,dispatch,rootState},data){
            return new Promise((resolve,reject)=>{
            Axios.put(rootState.url+"/api/user/"+data.id,data).then(response=>{
              dispatch('getMahasiswa')
              resolve(response)
            }).catch(error=>{
              reject(error)
            })})
        },
        deleteMahasiswa({commit,dispatch,rootState},id){
            Axios.delete(rootState.url+"/api/user/"+id).then(response=>{
              dispatch('getMahasiswa')
            })
        },
        importMahasiswa({commit,dispatch,rootState},data){
            Axios.post(rootState.url+"/api/user/import",data,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response=>{
              dispatch('getMahasiswa')
            })
        }
     },
    getters: {  }
}
