import { useAuthStore } from "@/stores/auth/auth"
import { useMutation } from '@tanstack/vue-query'
import type { IApiSuccess, ApiMessageResponse } from "@/interfaces/response.interface"
import type { ILoginResponse } from "@/interfaces/auth.interface"
import { authService } from "@/services/auth.services"
import { useRouter } from "vue-router"

export function useRefresh(){
    const authState = useAuthStore()
    const isRefreshing = authState.isRefreshing
    const route = useRouter()

    const refreshToken = useMutation<IApiSuccess<ILoginResponse>, ApiMessageResponse, unknown>({
        mutationFn: authService.refresh,
        onMutate: () => {
            authState.setRefreshing(true)
        },
        onSuccess: (response) => {
            if('data' in response){
                authState.setAuthData(response.data)    
                authState.setRefreshSuccessFul(true)
            } else {
                authState.setRefreshSuccessFul(false)
            }
        },
        onError: (error) => {
            console.error(`Refresh token thất bại: ${error}`);
            authState.clearAuthData()
            authState.setRefreshSuccessFul(false)
            authState.setRefreshing(false)
            route.push('/admin')
        }
    })

    return {
        isRefreshing,
        refreshToken,
        isRefreshingSuccessFul: authState.refreshSuccessFul,
        isLoading: refreshToken.isPending,
        error: refreshToken.error
    }
}   