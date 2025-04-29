interface IBaseApiResponse {
    status: boolean,
    message: string | undefined,
    timestamp: string
}

interface ICode {
    code: number
}
export interface ApiSuccessResponse<GenerateType> extends IBaseApiResponse, ICode {
    data: GenerateType
}

export interface ApiErrorResponse<Error> extends IBaseApiResponse, ICode {
    error: Error,
}

export interface ApiMessageResponse extends IBaseApiResponse {

}

export type ApiResponse<GenerateType, Error> = ApiSuccessResponse<GenerateType> | ApiErrorResponse<Error> | ApiMessageResponse

export type IApiErrorResponse<Error> = ApiErrorResponse<Error> | ApiMessageResponse

export type IApiSuccess<GenerateType> = ApiSuccessResponse<GenerateType> | ApiMessageResponse

export type IUnprocessableEntity = Record<string, string[]>