import axios from 'axios'

const API_BASE_URL = 'http://localhost/reservation-system/backend'

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})

export default {
  getReservations() {
    return api.get('/api/reservations')
  },
  
  getReservation(id) {
    return api.get(`/api/reservations/${id}`)
  },
  
  createReservation(data) {
    return api.post('/api/reservations', data)
  },
  
  updateReservation(id, data) {
    return api.put(`/api/reservations/${id}`, data)
  },
  
  deleteReservation(id) {
    return api.delete(`/api/reservations/${id}`)
  },
  
  getServices() {
    return api.get('/reservations/services')
  }
}