import { createRouter, createWebHistory } from 'vue-router'
import ReservationList from '../components/ReservationList.vue'
import CreateReservation from '../components/CreateReservation.vue'

const routes = [
  {
    path: '/',
    redirect: '/reservations'
  },
  {
    path: '/reservations',
    name: 'ReservationList',
    component: ReservationList
  },
  {
    path: '/create-reservation',
    name: 'CreateReservation',
    component: CreateReservation
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router