import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import Login from "../views/Login.vue";
import LoginPetugas from "../views/LoginPetugas.vue";
import Petugas from "../views/Petugas/Petugas.vue";
import Dashboard from "../views/Petugas/Admin/Dashboard.vue";
import Beasiswa from "../views/Petugas/Admin/Beasiswa.vue";
import Permohonan from "../views/Petugas/Admin/Permohonan.vue";
import Instansi from "../views/Petugas/Admin/Instansi.vue";
import Akun from "../views/Petugas/Admin/Akun.vue";
import AkunPetugas from "../views/Petugas/Admin/AkunPetugas.vue";
import KelolaMhs from "../views/Petugas/Admin/Mahasiswa.vue";
import CekPermohonan from "../views/Petugas/Admin/CekPermohonan.vue";

import Mahasiswa from "../views/Mahasiswa/Mahasiswa.vue";
import MHSHome from "../views/Mahasiswa/Home.vue";
import MHSPermohonan from "../views/Mahasiswa/PermohonanSaya.vue";
import MHSBeasiswa from "../views/Mahasiswa/Beasiswa.vue";

import Interviewer from "../views/Petugas/Interviewer/Interviewer.vue";
import InterviewerHome from "../views/Petugas/Interviewer/Home.vue";

import Surveyor from "../views/Petugas/Surveyor/Surveyor.vue";
import SurveyorHome from "../views/Petugas/Surveyor/Home.vue";

import Petinggi from "../views/Petugas/Petinggi/Petinggi.vue";
import PetinggiHome from "../views/Petugas/Petinggi/Home.vue";
import Axios from "axios";
// import Login from '../views/Login.vue'
// import Loket from '../views/Loket.vue'
// import Dashboard from '../views/Dashboard.vue'
// import axios from 'axios';
Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home
    },
    {
        path: "/login",
        name: "Login",
        component: Login
    },
    {
        path: "/login-petugas",
        name: "Login Petugas",
        component: LoginPetugas
    },
    //   {
    //     path: '/about',
    //     name: 'About',
    //     // route level code-splitting
    //     // this generates a separate chunk (about.[hash].js) for this route
    //     // which is lazy-loaded when the route is visited.
    //     component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
    //   },
    {
        path: "/:petugas",
        component: Petugas,
        async beforeEnter(to, from, next) {
            if (from.name == null) {
               await axios
                    .get("http://beasiswa.test/api/user/petugas")
                    .then(response => {
                        console.log(response.data);
                     next()
                    })
                    .catch(error => {
                        console.log(error.response.status);
                        next({name:"Login Petugas"});
                    });
            }else{
                next()
            }
        },
        children: [
            {
                name: "Dashboard",
                path: "dashboard",
                component: Dashboard
            },
            {
                name: "Beasiswa",
                path: "beasiswa",
                component: Beasiswa
            },
            {
                name: "Cek Berkas Permohonan",
                path: "cek-berkas-permohonan",
                component: CekPermohonan
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
        path: "/mahasiswa/",
        component: Mahasiswa,
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
        children: [
            {
                name: "Home",
                path: "home",
                component: InterviewerHome
            }
        ]
    },
    {
        path: "/surveyor/:petugas",
        component: Surveyor,
        children: [
            {
                name: "Home",
                path: "home",
                component: SurveyorHome
            }
        ]
    },
    {
        path: "/petinggi/:petugas",
        component: Petinggi,
        children: [
            {
                name: "Home",
                path: "home",
                component: PetinggiHome
            }
        ]
    }
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
