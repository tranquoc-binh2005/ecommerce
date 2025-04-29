import { defineStore } from 'pinia'
import type {IAuthState, ILoginResponse} from "@/interfaces/auth.interface.ts";

export const useAuthStore = defineStore('auth', {
    state: ():IAuthState => ({
        isLoginLoading: false,
        isRefreshing: false,
        refreshSuccessFul: null,
        authData: {
            accessToken: null,
            tokenType: null,
            expiresAt: null,
            user: null,
        }
    }),
    actions: {
        setIsLoginLoading(value: boolean) {
            this.isLoginLoading = value;
        },
        setAuthData(authData: ILoginResponse) {
            this.authData = {
                accessToken: authData.accessToken,
                expiresAt: authData.expiresAt,
                tokenType: authData.tokenType,
                user: authData.user,
            };
        },
        clearAuthData() {
            this.authData = {
                accessToken: null,
                tokenType: null,
                expiresAt: null,
                user: null,
            }
        },
        setRefreshing(value: boolean){
            this.isRefreshing = value
        },
        setRefreshSuccessFul(value: boolean){
            this.refreshSuccessFul = value
        }
    },
    getters: {
        isLoggedIn(): boolean {
            if(!this.authData.accessToken || !this.authData.expiresAt) {
                return false;
            }
            const currentTime = Math.floor(Date.now() / 1000)
            return currentTime < (this.authData.expiresAt) + currentTime
        },
        getAuthData(): ILoginResponse {
            return this.authData;
        }
    },
    persist: true
})