import axios, { AxiosError } from "axios"
import { useAuthStore } from "@/stores/auth/auth"

export const publicApi = axios.create({
    baseURL: '/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
    },
    withCredentials: true
})

export const privateApi = axios.create({
    baseURL: '/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
    },
    withCredentials: true
})

//interceptor
privateApi.interceptors.request.use(
    (config) => {
        const authStore = useAuthStore()
        const accessToken = authStore.authData.accessToken
        
        if(!accessToken){
            return Promise.reject(new Error("Không có accessToken phù hợp"))
        }
        config.headers.Authorization = `Bearer ${accessToken}`
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)

const handleError = (error: AxiosError) => {
    if(error.response?.status === 401) {
        //go accessToken
    }
    return Promise.reject(error)
}

publicApi.interceptors.response.use(
    (response) => response,
    handleError
)

privateApi.interceptors.response.use(
    (response) => response,
    handleError
)