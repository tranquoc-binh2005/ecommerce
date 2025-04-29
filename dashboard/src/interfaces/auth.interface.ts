import type {IUser} from "@/interfaces/user.interface.ts";

export interface ILoginRequest {
    email: string;
    password: string;
}

export interface ILoginResponse {
    accessToken: string | null
    expiresAt: number | null;
    tokenType: string | null;
    user: IUser | null;
}

export interface IAuthState {
    isLoginLoading: boolean,
    isRefreshing: boolean,
    refreshSuccessFul: boolean | null,
    authData: ILoginResponse
}