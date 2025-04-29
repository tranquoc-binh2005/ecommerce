import {privateApi, publicApi} from "@/config/axios.ts"
import type {ILoginRequest, ILoginResponse} from "@/interfaces/auth.interface.ts"
import type {ApiResponse, ApiSuccessResponse, IApiSuccess, IUnprocessableEntity} from "@/interfaces/response.interface.ts";

const ENDPOINT = "v1/auth"

export const authService = {
    login: async (payload: ILoginRequest): Promise<ApiSuccessResponse<ILoginResponse>> => {
        const response = await publicApi.post<ApiResponse<ILoginResponse, IUnprocessableEntity>>(`${ENDPOINT}/authenticate`, payload)
        if(response.data.status === true) {
            return response.data as ApiSuccessResponse<ILoginResponse>
        }
        throw response.data
    },
    refresh: async (): Promise<IApiSuccess<ILoginResponse>> => {
        const response = await privateApi.post(`${ENDPOINT}/refresh`)
        return response.data
    }
}