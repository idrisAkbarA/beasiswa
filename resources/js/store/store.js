import Vue from "vue";
import Vuex from "vuex";
import router from "../router/router";
import Axios from "axios";
import mhsModule from "./modules/mhsModule";
var pack = require("../../../package.json");
Vue.use(Vuex);
Axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
Axios.defaults.withCredentials = true;

export default new Vuex.Store({
    state: {
        appSettings: [],
        auth: { name: null, id: null, role: null, isAuth: false },
        report: [],
        url: pack.baseUrl,
        name: "idris",
        akunPetugas: [],
        nim: "",
        jurusan: [],
        lpj: [],
        fakultas: [],
        cekBerkas: [],
        cekInterview: [],
        cekSurvey: [],
        cekSelection: [],
        isLoading: false,
        isTableLoading: false,
        beasiswaProgress: {},
        beasiswa: [],
        beasiswaSingle: {},
        instansi: [],
        isOpenBeasiswa: false
    },
    mutations: {
        mutateAppSettings(state, data) {
            state.appSettings = data;
        },
        mutateReport(state, data) {
            state.report = data;
        },
        mutateAuth(state, data) {
            state.Auth = data;
        },
        mutateAkunPetugas(state, data) {
            state.akunPetugas = data;
        },
        mutateCekBerkas(state, data) {
            state.cekBerkas = data;
        },
        mutateCekInterview(state, data) {
            state.cekInterview = data;
        },
        mutateCekSurvey(state, data) {
            state.cekSurvey = data;
        },
        mutateCekSelection(state, data) {
            state.cekSelection = data;
        },
        mutateNim(state, data) {
            state.nim = data;
        },
        mutateLoading(state, data) {
            state.isLoading = data;
        },
        mutateTableLoading(state, data) {
            state.isTableLoading = data;
        },
        mutateBeasiswaProgress(state, data) {
            state.beasiswaProgress = data;
        },
        mutateBeasiswa(state, data) {
            state.beasiswa = data;
        },
        mutateLPJ(state, data) {
            state.lpj = data;
        },
        mutateBeasiswaSingle(state, data) {
            state.beasiswaSingle = data;
        },
        mutateInstansi(state, data) {
            state.instansi = data;
        },
        mutateFakultas(state, data) {
            state.fakultas = data;
        },
        mutateJurusan(state, data) {
            state.jurusan = data;
        },
        toggleOpenBeasiswa(state, data) {
            state.isOpenBeasiswa = data;
        }
    },
    actions: {
        getLPJ({ commit, dispatch, state }) {
            commit("mutateTableLoading", true);
            Axios.get("/api/lpj").then(response => {
                commit("mutateLPJ", response.data);
                commit("mutateTableLoading", false);
            });
        },
        getLPJActive({ commit, dispatch, state }) {
            commit("mutateTableLoading", true);
            Axios.get("/api/lpj/active").then(response => {
                commit("mutateLPJ", response.data);
                commit("mutateTableLoading", false);
            });
        },
        getAppSettings({ commit, dispatch, state }) {
            commit("mutateTableLoading", true);
            Axios.get("/api/beasiswa/settings")
                .then(response => {
                    commit("mutateAppSettings", response.data);
                    commit("mutateTableLoading", false);
                })
                .catch(error => {
                    // if (error.response.status == 401) {
                    //     router.push({ name: "Login Petugas" });
                    //   }
                });
        },
        getReport({ commit, dispatch, state }, data) {
            commit("mutateTableLoading", true);
            Axios.get("/api/beasiswa/report", {
                // params: { data }
                params: data
            }).then(response => {
                commit("mutateReport", response.data);
                commit("mutateTableLoading", false);
            });
        },
        getAkunPetugas({ commit, dispatch, state }) {
            commit("mutateTableLoading", true);
            Axios.get("/api/petugas")
                .then(response => {
                    commit("mutateAkunPetugas", response.data);
                    commit("mutateTableLoading", false);
                })
                .catch(error => {
                    if (error.response.status == 401) {
                        router.push({ name: "Login Petugas" });
                    }
                });
        },
        getCekBerkas({ commit, dispatch, state }) {
            commit("mutateTableLoading", true);
            Axios.get("/api/pemohon/cek-berkas").then(response => {
                commit("mutateCekBerkas", response.data);
                commit("mutateTableLoading", false);
            });
        },
        getCekInterview({ commit, dispatch, state }) {
            commit("mutateTableLoading", true);
            Axios.get("/api/pemohon/cek-interview").then(response => {
                commit("mutateCekInterview", response.data);
                commit("mutateTableLoading", false);
            });
        },
        getCekSurvey({ commit, dispatch, state }) {
            commit("mutateTableLoading", true);
            Axios.get("/api/pemohon/cek-survey").then(response => {
                commit("mutateCekSurvey", response.data);
                commit("mutateTableLoading", false);
            });
        },
        getBeasiswaSingle({ commit, dispatch, state }, id) {
            commit("mutateTableLoading", true);
            return new Promise((resolve, reject) => {
                Axios.get("/api/beasiswa/" + id)
                    .then(response => {
                        commit("mutateBeasiswaSingle", response.data);
                        commit("mutateTableLoading", false);
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        getBeasiswa({ commit, dispatch, state }) {
            commit("mutateTableLoading", true);
            Axios.get("/api/beasiswa")
                .then(response => {
                    commit("mutateBeasiswa", response.data);
                    commit("mutateTableLoading", false);
                })
                .catch(error => {
                    if (error.response.status == 401) {
                        router.push({ name: "Login Petugas" });
                    }
                });
        },
        getBeasiswaActive({ commit, dispatch, state }) {
            commit("mutateTableLoading", true);
            Axios.get("/api/beasiswa/get-active").then(response => {
                commit("mutateBeasiswa", response.data);
                commit("mutateTableLoading", false);
            }).catch(error => {
                if (error.response.status == 401) {
                    router.push({ name: "Landing Page" });
                }
            });
        },
        getBeasiswaSelesai({ commit, dispatch, state }) {
            commit("mutateTableLoading", true);
            Axios.get("/api/beasiswa/selesai").then(response => {
                commit("mutateBeasiswa", response.data);
                commit("mutateTableLoading", false);
            });
        },
        getBeasiswaNoAuth({ commit, dispatch, state }) {
            return new Promise((resolve, reject) => {
                commit("mutateTableLoading", true);
                Axios.get("/api/beasiswa/no-auth").then(response => {
                    commit("mutateBeasiswa", response.data);
                    commit("mutateTableLoading", false);
                    resolve(response.data);
                });
            });
        },
        getBeasiswaWithPermohonan({ commit, dispatch, state }, tahap) {
            commit("mutateTableLoading", true);
            return new Promise((resolve, reject) => {
                Axios.get("/api/beasiswa/with-permohonan", {
                    params: {
                        tahap: tahap
                    }
                }).then(response => {
                    commit("mutateBeasiswa", response.data);
                    commit("mutateTableLoading", false);
                    resolve(response);
                }).catch(error => {
                    if (error.response.status == 401) {
                        router.push({ name: "Login Petugas" });
                    }
                });
            });
        },
        storeAkunPetugas({ commit, dispatch, state }, data) {
            return new Promise((resolve, reject) => {
                Axios.post("/api/petugas", data)
                    .then(response => {
                        dispatch("getAkunPetugas");
                        resolve(response);
                    })
                    .catch(error => {
                        if (error.response.status == 401) {
                            router.push({ name: "Login Petugas" });
                        }
                        reject(error);
                    });
            });
        },
        editAkunPetugas({ commit, dispatch, state }, data) {
            return new Promise((resolve, reject) => {
                console.log(data);
                Axios.put("/api/petugas/" + data.id, {
                    name: data.name,
                    password: data.password,
                    role: data.role
                })
                    .then(response => {
                        dispatch("getAkunPetugas");
                        resolve(response);
                    })
                    .catch(error => {
                        if (error.response.status == 401) {
                            router.push({ name: "Login Petugas" });
                        }
                        reject(error);
                    });
            });
        },
        editAppSettings({ commit, dispatch, state }, data) {
            return new Promise((resolve, reject) => {
                console.log(data, "woi");
                Axios.put("/api/beasiswa/settings", {
                    settings: data
                })
                    .then(response => {
                        dispatch("getAppSettings");
                        resolve(response);
                    })
                    .catch(error => {
                        if (error.response.status == 401) {
                            router.push({ name: "Login Petugas" });
                        }
                        reject(error);
                    });
            });
        },
        deleteAkunPetugas({ commit, dispatch, state }, data) {
            return new Promise((resolve, reject) => {
                Axios.delete("/api/petugas/" + data.id)
                    .then(response => {
                        dispatch("getAkunPetugas");
                        resolve(response);
                    })
                    .catch(error => {
                        if (error.response.status == 401) {
                            router.push({ name: "Login Petugas" });
                        }
                        reject(error);
                    });
            });
        },
        editBeasiswa({ commit, dispatch, state }, data) {
            return new Promise((resolve, reject) => {
                Axios.put("/api/beasiswa/" + data.id, {
                    id: data.id,
                    nama: data.nama,
                    deskripsi: data.deskripsi,
                    kuota: data.kuota,
                    instansi_id: data.instansi_id,
                    instansi: data.instansi,
                    fields: data.fields,
                    is_survey: data.is_survey,
                    is_wawancara: data.is_wawancara,
                    awal_interview: data.awal_interview,
                    akhir_interview: data.akhir_interview,
                    awal_survey: data.awal_survey,
                    akhir_survey: data.akhir_survey,
                    awal_berkas: data.awal_berkas,
                    akhir_berkas: data.akhir_berkas,
                    is_first: data.is_first,
                    ukt: data.ukt,
                    ipk: data.ipk,
                    semester: data.semester
                })
                    .then(response => {
                        dispatch("getBeasiswa");
                        dispatch("getInstansi");
                        resolve(response);
                    })
                    .catch(error => {
                        if (error.response.status == 401) {
                            router.push({ name: "Login Petugas" });
                        }
                        reject(error);
                    });
            });
        },
        storeBeasiswa({ commit, dispatch, state }, data) {
            return new Promise((resolve, reject) => {
                Axios.post("/api/beasiswa", { data })
                    .then(response => {
                        dispatch("getBeasiswa");
                        dispatch("getInstansi");
                        resolve(response);
                    })
                    .catch(error => {
                        if (error.response.status == 401) {
                            router.push({ name: "Login Petugas" });
                        }
                        reject(error);
                    });
            });
        },
        storeInstansi({ commit, dispatch, state }, data) {
            return new Promise((resolve, reject) => {
                Axios.post("/api/instansi", data)
                    .then(response => {
                        dispatch("getInstansi");
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        getInstansi({ commit, dispatch, state }) {
            commit("mutateTableLoading", true);
            Axios.get("/api/instansi")
                .then(response => {
                    commit("mutateInstansi", response.data);
                    commit("mutateTableLoading", false);
                })
                .catch(error => {
                    if (error.response.status == 401) {
                        router.push({ name: "Login Petugas" });
                    }
                });
        },
        deleteInstansi({ commit, dispatch, state }, id) {
            Axios.delete("/api/instansi/" + id).then(response => {
                dispatch("getInstansi");
            });
        },
        selesaiBeasiswa({ commit, dispatch, state }, id) {
            Axios.delete("/api/beasiswa/selesai/" + id).then(response => {
                dispatch("getBeasiswaWithPermohonan");
            });
        },
        deleteBeasiswa({ commit, dispatch, state }, id) {
            Axios.delete("/api/beasiswa/" + id).then(response => {
                dispatch("getBeasiswa");
            });
        },
        deleteBeasiswaWithPermohonan({ commit, dispatch, state }, id) {
            Axios.delete("/api/beasiswa/" + id).then(response => {
                dispatch("getBeasiswaWithPermohonan");
            });
        },
        editInstansi({ commit, dispatch, state }, data) {
            Axios.put("/api/instansi/" + data.id, {
                name: data.name
            }).then(response => {
                dispatch("getInstansi");
            });
        },
        getFakultas({ commit, dispatch, state }) {
            Axios.get("/api/fakultas").then(response => {
                commit("mutateFakultas", response.data);
            });
        },
        getJurusan({ commit, dispatch, state }) {
            Axios.get("/api/jurusan").then(response => {
                commit("mutateJurusan", response.data);
            });
        },
        searchPermohonan({ commit, dispatch, state }, query) {
            Axios.get("/api/pemohon/search", {
                params: {
                    q: query
                }
            });
        },
        cekPersyaratan({ commit, dispatch, state }, id) {
            Axios.get("/api/beasiswa/cek/" + id);
        }
    },
    modules: {
        mhs: mhsModule
    }
});
