import Axios from 'axios'

export default {
    state: () => ({
        mahasiswa: []
    }),
    mutations: {
        mutateMahasiswa(state, data) {
            state.mahasiswa = data;
        },
        mutateBeasiswa(rootState, data) {
            rootState.beasiswa = data;
        },
    },
    actions: {
        getMahasiswa({ commit, dispatch, rootState }) {
            commit("mutateTableLoading", true);
            Axios.get(rootState.url + "/api/user").then(response => {
                commit('mutateMahasiswa', response.data)
                commit("mutateTableLoading", false);
            })
        },
        storeMahasiswa({ commit, dispatch, rootState }, data) {
            return new Promise((resolve, reject) => {
                Axios.post(rootState.url + "/api/user", data).then(response => {
                    dispatch('getMahasiswa')
                    resolve(response)
                }).catch(error => {
                    reject(error)
                })
            })
        },
        editMahasiswa({ commit, dispatch, rootState }, data) {
            return new Promise((resolve, reject) => {
                Axios.put(rootState.url + "/api/user/" + data.nim, data).then(response => {
                    dispatch('getMahasiswa')
                    resolve(response)
                }).catch(error => {
                    reject(error)
                })
            })
        },
        deleteMahasiswa({ commit, dispatch, rootState }, nim) {
            Axios.delete(rootState.url + "/api/user/" + nim).then(response => {
                dispatch('getMahasiswa')
            })
        },
        importMahasiswa({ commit, dispatch, rootState }, data) {
            Axios.post(rootState.url + "/api/user/import", data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                dispatch('getMahasiswa')
            })
        },
        getBeasiswaSelesai({ commit, dispatch, rootState }) {
            commit("mutateTableLoading", true);
            Axios.get(rootState.url + "/api/beasiswa/selesai").then(response => {
                commit('mutateBeasiswaProgress', response.data);
                commit("mutateTableLoading", false);
            })
        },
    },
    getters: {}
}
