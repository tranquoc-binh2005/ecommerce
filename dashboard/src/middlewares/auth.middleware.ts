import {useAuthStore} from "@/stores/auth/auth.ts";
import {getActivePinia} from "pinia";
import { useRefresh } from "@/hook/auth/useRefresh";

export const authenticatedMiddleware = (to: any, from: any, next: any) => {
    const authStore = useAuthStore(getActivePinia());
    // authStore.clearAuthData()
    if(authStore.isLoggedIn){
        return next({
            name: "dashboard.index"
        });
    }
    next();
}
export const notAuthenticatedMiddleware = (to: any, from: any, next: any) => {
    const authStore = useAuthStore(getActivePinia());
    const {refreshToken} = useRefresh()

    if(!authStore.isLoggedIn && !authStore.authData.accessToken){
        return next({
            name: "admin.signin"
        })
    }

    refreshToken.mutate(undefined)
    next();
}