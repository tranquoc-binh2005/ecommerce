import {useMutation} from "@tanstack/vue-query"
import type {ApiSuccessResponse, IUnprocessableEntity, IApiErrorResponse} from "@/interfaces/response.interface.ts"
import type {ILoginResponse, ILoginRequest} from "@/interfaces/auth.interface.ts"
import {authService} from "@/services/auth.services.ts"
import {useNotification} from "@/stores/notification";
import {useAuthStore} from "@/stores/auth/auth.ts";
import {useRouter} from "vue-router"
import {AxiosError} from "axios";

export function useAuth(){

    const notification = useNotification()
    const authStore = useAuthStore()
    const router = useRouter()

    // const handleAuthError = (error: AxiosError<IApiErrorResponse<IUnprocessableEntity>>) => {
    //     if (error && error.response) {
    //         const errors = error.response?.data?.errors;
    //         let errorMessages: string[] = [];
    //
    //         Object.keys(errors).forEach((field) => {
    //             errors[field].forEach((message: string) => {
    //                 errorMessages.push(`${message}. `);
    //             });
    //         });
    //
    //         if (errorMessages.length) {
    //             notification.setPendingNotifications({
    //                 type: "error",
    //                 message: errorMessages.join(""),
    //             });
    //         }
    //     } else {
    //         notification.setPendingNotifications({
    //             type: "error",
    //             message: error.response?.data.message ?? "Lỗi hệ thống",
    //         });
    //     }
    // };


    const loginMutation = useMutation<ApiSuccessResponse<ILoginResponse>, AxiosError<IApiErrorResponse<IUnprocessableEntity>>, ILoginRequest>({
        mutationFn: authService.login,
        onSuccess: (response: ApiSuccessResponse<ILoginResponse>) => {
            notification.setPendingNotifications({
                type: "success",
                message: "Đăng nhập thành công vào hệ thống",
            })
            authStore.setAuthData(response.data)
            router.push("/dashboard")
        },
        onError: (error: AxiosError<IApiErrorResponse<IUnprocessableEntity>>) => {
            notification.setPendingNotifications({
                type: "error",
                message: error.response?.data.message ?? "Lỗi hệ thống",
            });
        }
    })

    return {
        loginMutation,
    }
}