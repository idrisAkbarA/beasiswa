// var pack = require("../../../package.json");
import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import Unauthorized from "../views/Unauthorized.vue";
import Test from "../views/Test.vue";
import NotFound from "../views/404.vue";
import Login from "../views/Login.vue";
import LoginPetugas from "../views/LoginPetugas.vue";
import Petugas from "../views/Petugas/Petugas.vue";
import Dashboard from "../views/Petugas/Admin/Dashboard.vue";
import Beasiswa from "../views/Petugas/Admin/Beasiswa.vue";
import Permohonan from "../views/Petugas/Admin/Permohonan.vue";
import Instansi from "../views/Petugas/Admin/Instansi.vue";
import Akun from "../views/Petugas/Admin/Akun.vue";
import AkunPetugas from "../views/Petugas/Admin/AkunPetugas.vue";
import Kelulusan from "../views/Petugas/Admin/Kelulusan.vue";
import KelolaMhs from "../views/Petugas/Admin/Mahasiswa.vue";
import LPJ from "../views/Petugas/Admin/LPJ.vue";
import Laporan from "../views/Petugas/Admin/Laporan.vue";
import Pengaturan from "../views/Petugas/Admin/Pengaturan.vue";

import SuperAdmin from "../views/Petugas/SuperAdmin/SuperAdmin.vue";
import SAKelolaMhs from "../views/Petugas/SuperAdmin/Mahasiswa.vue";
import SAPermohonan from "../views/Petugas/SuperAdmin/Permohonan.vue";
import SAInstansi from "../views/Petugas/SuperAdmin/Instansi.vue";
import SAAkunPetugas from "../views/Petugas/SuperAdmin/Petugas.vue";
import SABeasiswa from "../views/Petugas/SuperAdmin/Beasiswa.vue";

import Mahasiswa from "../views/Mahasiswa/Mahasiswa.vue";
import MHSHome from "../views/Mahasiswa/Home.vue";
import MHSPermohonan from "../views/Mahasiswa/PermohonanSaya.vue";
import MHSBeasiswa from "../views/Mahasiswa/Beasiswa.vue";

import Interviewer from "../views/Petugas/Interviewer/Interviewer.vue";
import InterviewerHome from "../views/Petugas/Interviewer/Home.vue";
import InterviewerHistory from "../views/Petugas/Interviewer/History.vue";

import Surveyor from "../views/Petugas/Surveyor/Surveyor.vue";
import SurveyorHome from "../views/Petugas/Surveyor/Home.vue";
import SurveyorHistory from "../views/Petugas/Surveyor/History.vue";

import Petinggi from "../views/Petugas/Petinggi/Petinggi.vue";
import PetinggiHome from "../views/Petugas/Petinggi/Home.vue";

import Verificator from "../views/Petugas/Verificator/Verificator.vue";
import VerificatorHome from "../views/Petugas/Verificator/Home.vue";
import VerificatorHistory from "../views/Petugas/Verificator/History.vue";
import VerificatorReport from "../views/Petugas/Verificator/Laporan.vue";

import store from "../store/store";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "Landing Page",
        component: Home
    },
    {
        path: "/test",
        name: "Landing Page",
        component: Test
    },
    {
        path: "/unauthorized",
        name: "Unauthorized",
        component: Unauthorized
    },
    {
        path: "/login-mhs",
        name: "Login Mahasiswa",
        component: Login
    },
    {
        path: "/login-petugas",
        name: "Login Petugas",
        component: LoginPetugas
    },
    {
        path: "/admin/:petugas",
        component: Petugas,
        beforeRouteUpdate: (next) => {
            console.log("patek")
            axios
                .get("/api/user/petugas")
                .then(response => {
                    console.log(response.data);
                    response.data.role == 1
                        ? next()
                        : next({ name: "Unauthorized" });
                })
                .catch(error => {
                    console.log("---------");
                    console.log(error);
                    console.log("---------");
                    next({ name: "Landing Page" });
                });
        },
        async beforeEnter(to, from, next) {
            if (from.name == null) {
                await axios
                    .get("/api/user/petugas")
                    .then(response => {
                        console.log(response.data);
                        response.data.role == 1
                            ? next()
                            : next({ name: "Unauthorized" });
                    })
                    .catch(error => {
                        console.log("---------");
                        console.log(error);
                        console.log("---------");
                        next({ name: "Landing Page" });
                    });
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth != true
            ) {
                console.log("Op tidak bisa");
                next(false);
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth == true
            ) {
                next();
            }
        },
        children: [
            {
                name: "Dashboard",
                path: "dashboard",
                component: Dashboard
            },
            {
                name: "Kelulusan",
                path: "kelulusan",
                component: Kelulusan
            },
            {
                name: "Pengaturan",
                path: "pengaturan",
                component: Pengaturan
            },
            {
                name: "Laporan",
                path: "laporan",
                component: Laporan
            },
            {
                name: "Beasiswa",
                path: "beasiswa",
                component: Beasiswa
            },
            {
                name: "LPJ",
                path: "LPJ",
                component: LPJ
            },
            {
                name: "List Permohonan",
                path: "permohonan",
                component: Permohonan
            },
            {
                name: "Instansi",
                path: "instansi",
                component: Instansi
            },
            {
                name: "Akun Petugas",
                path: "petugas",
                component: AkunPetugas
            },
            {
                name: "Akun",
                path: "akun",
                component: Akun
            },
            {
                name: "Kelola Mahasiswa",
                path: "mahasiswa",
                component: KelolaMhs
            }
        ]
    },
    {
        path: "/super/:petugas",
        component: SuperAdmin,
        async beforeEnter(to, from, next) {
            if (from.name == null) {
                await axios
                    .get("/api/user/petugas")
                    .then(response => {
                        console.log(response.data);
                        response.data.role == 0
                            ? next()
                            : next({ name: "Unauthorized" });
                    })
                    .catch(error => {
                        console.log("---------");
                        console.log(error);
                        console.log("---------");
                        next({ name: "Landing Page" });
                    });
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth != true
            ) {
                console.log("Op tidak bisa");
                next(false);
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth == true
            ) {
                next();
            }
        },
        children: [
            {
                name: "Beasiswa",
                path: "beasiswa",
                component: SABeasiswa
            },
            {
                name: "List Permohonan",
                path: "permohonan",
                component: SAPermohonan
            },
            {
                name: "Instansi",
                path: "instansi",
                component: SAInstansi
            },
            {
                name: "Akun Petugas",
                path: "petugas",
                component: SAAkunPetugas
            },
            {
                name: "Kelola Mahasiswa",
                path: "mahasiswa",
                component: SAKelolaMhs
            }
        ]
    },

    {
        path: "/mahasiswa/",
        component: Mahasiswa,
        async beforeEnter(to, from, next) {
            if (from.name == null) {
                await axios
                    .get("/api/user/mahasiswa")
                    .then(response => {
                        console.log(response.data);
                        next();
                    })
                    .catch(error => {
                        console.log("---------");
                        console.log(error);
                        console.log("---------");
                        next({ name: "Landing Page" });
                    });
            } else if (
                from.name == "Landing Page" &&
                store.state.auth.isAuth != true
            ) {
                console.log("Op tidak bisa");
                next(false);
            } else if (
                from.name == "Landing Page" &&
                store.state.auth.isAuth == true
            ) {
                next();
            } else if (
                from.name == "Login Mahasiswa" &&
                store.state.auth.isAuth == true
            ) {
                next();
            }
        },
        children: [
            {
                name: "List Beasiswa",
                path: "home",
                component: MHSHome
            },
            {
                name: "Daftar Beasiswa",
                path: "daftar-beasiswa/:id",
                component: MHSBeasiswa
            },
            {
                name: "Permohonan Saya",
                path: "permohonan-saya",
                component: MHSPermohonan
            }
        ]
    },
    {
        path: "/interviewer/:petugas",
        component: Interviewer,
        async beforeEnter(to, from, next) {
            if (from.name == null) {
                await axios
                    .get("/api/user/petugas")
                    .then(response => {
                        store.state.auth.role = response.data.role;
                        store.state.auth.nama = response.data.nama_lengkap;
                        store.state.auth.fakultas = response.data.fakultas;
                        store.state.auth.id = response.data.id;
                        store.state.auth.isAuth = true;
                        console.log(response.data);
                        response.data.role == 2
                            ? next()
                            : next({ name: "Unauthorized" });
                    })
                    .catch(error => {
                        console.log("---------");
                        console.log(error);
                        console.log("---------");
                        next({ name: "Landing Page" });
                    });
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth != true
            ) {
                console.log("Op tidak bisa");
                next(false);
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth == true
            ) {
                next();
            }
        },
        children: [
            {
                name: "Home",
                path: "home",
                component: InterviewerHome
            },
            {
                name: "Interviewer History",
                path: "history",
                component: InterviewerHistory
            }
        ]
    },
    {
        path: "/surveyor/:petugas",
        component: Surveyor,
        async beforeEnter(to, from, next) {
            if (from.name == null) {
                await axios
                    .get("/api/user/petugas")
                    .then(response => {
                        console.log(response.data);
                        response.data.role == 3
                            ? next()
                            : next({ name: "Unauthorized" });
                    })
                    .catch(error => {
                        console.log("---------");
                        console.log(error);
                        console.log("---------");
                        next({ name: "Landing Page" });
                    });
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth != true
            ) {
                console.log("Op tidak bisa");
                next(false);
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth == true
            ) {
                next();
            }
        },
        children: [
            {
                name: "Home",
                path: "home",
                component: SurveyorHome
            },
            {
                name: "Surveyor History",
                path: "history",
                component: SurveyorHistory
            }
        ]
    },
    {
        path: "/petinggi/:petugas",
        component: Petinggi,
        async beforeEnter(to, from, next) {
            if (from.name == null) {
                await axios
                    .get("/api/user/petugas")
                    .then(response => {
                        console.log(response.data);
                        response.data.role == 4
                            ? next()
                            : next({ name: "Unauthorized" });
                    })
                    .catch(error => {
                        console.log("---------");
                        console.log(error);
                        console.log("---------");
                        next({ name: "Landing Page" });
                    });
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth != true
            ) {
                console.log("Op tidak bisa");
                next(false);
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth == true
            ) {
                next();
            }
        },
        children: [
            {
                name: "Home",
                path: "home",
                component: PetinggiHome
            }
        ]
    },
    {
        path: "/verificator/:petugas",
        component: Verificator,
        async beforeEnter(to, from, next) {
            if (from.name == null) {
                await axios
                    .get("/api/user/petugas")
                    .then(response => {
                        store.state.auth.role = response.data.role;
                        store.state.auth.nama = response.data.nama_lengkap;
                        store.state.auth.fakultas = response.data.fakultas;
                        store.state.auth.id = response.data.id;
                        store.state.auth.isAuth = true;
                        console.log(response.data);
                        response.data.role == 5
                            ? next()
                            : next({ name: "Unauthorized" });
                    })
                    .catch(error => {
                        console.log("---------");
                        console.log(error);
                        console.log("---------");
                        next({ name: "Landing Page" });
                    });
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth != true
            ) {
                console.log("Op tidak bisa");
                next(false);
            } else if (
                from.name == "Login Petugas" &&
                store.state.auth.isAuth == true
            ) {
                next();
            }
        },
        children: [
            {
                name: "Home",
                path: "home",
                component: VerificatorHome
            },
            {
                name: "Verificator History",
                path: "history",
                component: VerificatorHistory
            },
            {
                name: "Verificator Report",
                path: "laporan",
                component: VerificatorReport
            }
        ]
    },
    {
        path: "/*",
        name: "Not Found",
        component: NotFound
    },
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes
});

// router.beforeEach((to, from, next) => {
//   if (to.name !== 'Login' && !isAuthenticated) next({ name: 'Login' })
//   else next()
// })
export default router;
