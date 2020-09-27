import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import LoginPetugas from '../views/LoginPetugas.vue'
import Petugas from '../views/Petugas/Petugas.vue'
import Dashboard from '../views/Petugas/Admin/Dashboard.vue'
import Beasiswa from '../views/Petugas/Admin/Beasiswa.vue'
import Permohonan from '../views/Petugas/Admin/Permohonan.vue'
import Mahasiswa from '../views/Mahasiswa/Mahasiswa.vue'
import MHSHome from '../views/Mahasiswa/Home.vue'
import MHSBeasiswa from '../views/Mahasiswa/Beasiswa.vue'

// import Login from '../views/Login.vue'
// import Loket from '../views/Loket.vue'
// import Dashboard from '../views/Dashboard.vue'
// import axios from 'axios';
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/login-petugas',
    name: 'Login Petugas',
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
    path: '/:petugas',
    component: Petugas,
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
        name: "Permohonan",
        path: "permohonan",
        component: Permohonan
      }, 
    ]
  },
  {
    path: '/mahasiswa/',
    component: Mahasiswa,
    children: [
      {
        name: "Home",
        path: "home",
        component: MHSHome
      }, 
      {
        name: "Daftar Beasiswa",
        path: "daftar-beasiswa/:id",
        component: MHSBeasiswa
      }, 
    ]
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})
// router.beforeEach((to, from, next) => {
//   if (to.name !== 'Login' && !isAuthenticated) next({ name: 'Login' })
//   else next()
// })
export default router
