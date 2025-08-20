import axios from "axios"

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL + '/api', 
  timeout: 10000,
  headers: {
    "Content-Type": "application/json"
  }
})

api.interceptors.request.use(
  (config) => {
    const token = import.meta.env.VITE_API_TOKEN
    if (token) {
      config.headers = config.headers || {}
      config.headers.Authorization = `Bearer ${token}`
    } else {
      delete config.headers?.Authorization
    }
    return config
  },
  (error) => Promise.reject(error)
)

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      console.warn("Não autorizado, faça login novamente.")
    }
    return Promise.reject(error)
  }
)

export default api
