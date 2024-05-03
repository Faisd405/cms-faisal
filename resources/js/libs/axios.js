import axios from 'axios'
import { useAuthStore } from '../store/authStore'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['Content-Type'] = 'application/json'

axios.interceptors.request.use((config) => {
    const token = localStorage.getItem('token')

    if (
        token !== null &&
        token !== undefined &&
        !(
            config.headers.hasOwnProperty || Object.prototype.hasOwnProperty
        ).call(config.headers, 'Authorization')
    ) {
        config.headers.Authorization = `Bearer ${token}`
    }

    return config
})

axios.interceptors.response.use(
    (response) => {
        return response
    },
    (error) => {
        if (useAuthStore().token && error?.response?.status === 401) {
            localStorage.removeItem('user')
            localStorage.removeItem('token')

            location.href = '/auth/login'
        }
        throw error
    }
)

let token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

export default axios
