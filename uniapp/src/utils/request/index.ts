import { merge } from 'lodash-es'
import sysConfig from '@/config'
import HttpRequest from './http'
import useUserStore from '@/stores/user'
import clientUtil from '@/utils/client'
import { ErrorEnum } from '@/enums/errors'
import type {
    RequestOptions,
    RequestConfigs,
    RequestHooks,
    ResponseResult
} from './type'

const requestHooks: RequestHooks = {
    /**
     * 请求拦截
     *
     * @param {RequestOptions} options 请求参数
     * @param {RequestConfigs} config 基础参数
     * @author zero
     */
    requestInterceptorsHook(options: RequestOptions, config: RequestConfigs) {
        // 接收配置
        const baseUrl: string = config?.baseUrl
        const urlPrefix: string = config?.urlPrefix
        const withToken: boolean = config?.withToken

        // 拼接前缀
        if (urlPrefix) {
            options.url = `${urlPrefix}${options.url}`
        }

        // 拼接地址
        if (baseUrl) {
            options.url = `${baseUrl}${options.url}`
        }

        // 头部参数
        options.header = options.header || {}
        options.header.Terminal = clientUtil.fetchClient()
        if (withToken && !options.header.token) {
            options.header.Token = useUserStore().token
        }

        // 返回参数
        return options
    },

    /**
     * 响应拦截
     *
     * @param {ResponseResult} response 响应结果
     * @param {RequestConfigs} config 基础参数
     * @param {RequestOptions} options 请求参数
     * @author zero
     */
    async responseInterceptorsHook(
        response: ResponseResult,
        config: RequestConfigs,
        options: RequestOptions
    ): Promise<any> {
        // 接收配置
        const isTransformResponse: boolean = config.isTransformResponse
        const isReturnDefaultResponse: boolean = config.isReturnDefaultResponse

        // 用户存储
        const userStore = useUserStore()

        // 需响应头及其它时可使用
        if (isReturnDefaultResponse) {
            return response
        }

        // 不需要对数据进行处理时
        if (!isTransformResponse) {
            return response.data
        }

        // 对响应回的数据进行处理
        const { code, msg, data } = response.data as any
        switch (code) {
            case ErrorEnum.SUCCESS:
                return data
            case ErrorEnum.SYSTEM_ERROR:
            case ErrorEnum.PARAMS_ERROR:
            case ErrorEnum.METHOD_ERROR:
            case ErrorEnum.CONTROL_ERROR:
            case ErrorEnum.REQUEST_ERROR:
            case ErrorEnum.OPERATE_ERROR:
            case ErrorEnum.UPLOADS_ERROR:
            case ErrorEnum.PURVIEW_ERROR:
                await uni.showToast({
                    title: msg,
                    icon: 'none',
                    duration: 1500
                })
                return Promise.reject(response.data)
            case ErrorEnum.LOGIN_EMPTY_ERROR:
                userStore.logout()
                return uni.reLaunch({ url: '/pages/index/index' })
            case ErrorEnum.LOGIN_EXPIRE_ERROR:
                userStore.logout()
                if (options.method?.toUpperCase() !== 'GET') {
                    await uni.navigateTo({
                        url: '/pages/login/index'
                    })
                }
                return Promise.reject(response.data)
            default:
                return data
        }
    },

    /**
     * 失败拦截
     *
     * @param {RequestOptions} options
     * @param error
     * @author zero
     */
    async responseInterceptorsCatchHook(options: RequestOptions, error): Promise<any> {
        if (options.method?.toUpperCase() === 'POST') {
            await uni.showToast({
                title: '请求失败,请重试!',
                icon: 'none',
                duration: 2000
            })
        }
        return error.errMsg || error
    }
}

const defaultConfig: RequestConfigs = {
    requestOptions: {
        // 请求超时时间毫秒
        timeout: sysConfig.timeout
    },
    // 请求接口域名
    baseUrl: sysConfig.baseUrl,
    // 请求接口前缀
    urlPrefix: sysConfig.urlPrefix,
    // 自动携带令牌
    withToken: true,
    // 忽略重复请求
    ignoreCancel: false,
    // 返回数据进行处理
    isTransformResponse: true,
    // 是否返回默认数据
    isReturnDefaultResponse: false,
    // 重试请求最大次数
    retryCount: sysConfig.retryCount || 2,
    // 重试请求间隔时长(ms)
    retryTimeout: sysConfig.retryTimeout || 1000,
    // 请求拦截钩子事件
    requestHooks: requestHooks
}

function createRequest(opt?: RequestConfigs): HttpRequest {
    return new HttpRequest(
        merge(defaultConfig, opt || {})
    )
}

const request: HttpRequest = createRequest()
export default request
