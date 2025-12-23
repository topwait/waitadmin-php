export type {
    RequestTask,
    RequestOptions,
    UploadFileOption
} from 'uni-app'

// 响应结果类型
export type ResponseResult =
    | UniApp.RequestSuccessCallbackResult
    | UniApp.UploadFileSuccessCallbackResult

// 请求配置类型
export interface RequestConfigs {
    requestOptions: Partial<RequestOptions>,
    // 请求接口域名
    baseUrl: string;
    // 请求接口前缀
    urlPrefix: string;
    // 自动携带令牌
    withToken: boolean;
    // 忽略重复请求
    ignoreCancel: boolean;
    // 请求拦截钩子事件
    requestHooks: RequestHooks;
    // 返回数据进行处理
    isTransformResponse: boolean;
    // 是否返回默认数据
    isReturnDefaultResponse: boolean;
    // 重试请求最大次数
    retryCount?: number;
    // 重试请求间隔时长(ms)
    retryTimeout?: number;
    // 重试请求当前次数
    retryCurrent?: number;
    // 上传进度回调函数
    onProgress?: (progress: number) => void;
}

// HTTP拦截钩子
export interface RequestHooks {
    requestInterceptorsHook?(
        options: RequestOptions,
        config: RequestConfigs
    ): RequestOptions

    responseInterceptorsHook?(
        response: ResponseResult,
        config: RequestConfigs,
        options: RequestOptions
    ): any

    responseInterceptorsCatchHook?(
        options: RequestOptions,
        error: any
    ): any
}

// SSE拦截钩子
export interface RequestStreamHooks extends Partial<RequestConfigs> {
    onStart?: (event: ReadableStreamDefaultReader | UniApp.RequestTask) => void
    onMessage?: (value: string) => void
    onClose?: () => void
}
