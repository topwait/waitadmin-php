import { merge } from 'lodash-es'
import { parse } from 'jsonc-parser'
import { isFunction, isObject } from '@vue/shared'
import requestCancel from './cancel'
import type {
    UploadFileOption,
    RequestOptions,
    RequestConfigs,
    RequestHooks,
    RequestStreamHooks
} from './type'

export default class HttpRequest {
    private readonly reqConfig: RequestConfigs

    constructor(config: RequestConfigs) {
        this.reqConfig = config
    }

    /**
     * GET请求
     *
     * @param {RequestOptions} options 请求参数
     * @param {Partial<RequestConfigs>} config 基础参数
     * @returns {Promise<any>}
     * @author zero
     */
    get<T = any>(options: RequestOptions, config?: Partial<RequestConfigs>): Promise<T> {
        return this.request(
            { ...options, method: 'GET' },
            config
        )
    }

    /**
     * POST请求
     *
     * @param {RequestOptions} options 请求参数
     * @param {Partial<RequestConfigs>} config 基础参数
     * @returns {Promise<any>}
     * @author zero
     */
    post<T = any>(options: RequestOptions, config?: Partial<RequestConfigs>): Promise<T> {
        return this.request(
            { ...options, method: 'POST' },
            config
        )
    }

    /**
     * 重试请求
     *
     * @param {RequestOptions} options 请求参数
     * @param {Partial<RequestConfigs>} config 基础参数
     * @returns {Promise<any>}
     * @author zero
     */
    async retryRequest<T = any>(options: RequestOptions, config: RequestConfigs): Promise<T> {
        const { retryCount, retryTimeout } = config
        if (!retryCount || options.method?.toUpperCase() === 'POST') {
            return Promise.reject()
        }

        await uni.showLoading({ title: '加载中...' })

        config.retryCurrent = config.retryCurrent || 0
        if (config.retryCurrent >= retryCount) {
            return Promise.reject()
        }

        config.retryCurrent++
        config.requestHooks.requestInterceptorsHook = (options: RequestOptions) => options
        return new Promise((resolve) => setTimeout(resolve, retryTimeout))
            .then(() => this.request(options, config))
            .finally(() => uni.hideLoading())
    }

    /**
     * 发起请求
     *
     * @param {RequestOptions} options 请求参数
     * @param {Partial<RequestConfigs>} config 基础参数
     * @returns {Promise<any>}
     * @author zero
     */
    async request<T = any>(options: RequestOptions, config?: Partial<RequestConfigs>): Promise<T> {
        let mergeOptions: RequestOptions = merge(
            {},
            this.reqConfig.requestOptions,
            options
        )

        if (mergeOptions?.params) {
            const data = mergeOptions.data || {}
            const params = mergeOptions?.params
            delete mergeOptions.params
            mergeOptions.data = Object.assign({}, data, params)
        }

        const mergeConfig: RequestConfigs = merge({}, this.reqConfig, config)
        const {
            requestInterceptorsHook,
            responseInterceptorsHook,
            responseInterceptorsCatchHook
        } = mergeConfig.requestHooks || {}

        if (requestInterceptorsHook && isFunction(requestInterceptorsHook)) {
            mergeOptions = requestInterceptorsHook(mergeOptions, mergeConfig)
        }

        return new Promise((resolve, reject): void => {
            const requestTask = uni.request({
                ...mergeOptions,
                async success(response: any): Promise<void> {
                    if (response.statusCode !== 200) {
                        return reject()
                    }
                    if (responseInterceptorsHook && isFunction(responseInterceptorsHook)) {
                        try {
                            response = await responseInterceptorsHook(
                                response,
                                mergeConfig,
                                mergeOptions
                            )
                            resolve(response)
                        } catch (error) {
                            reject(error)
                        }
                        return
                    }
                    resolve(response)
                },
                fail: async (err: any): Promise<void> => {
                    if (err.errMsg === 'request:fail timeout') {
                        this.retryRequest(mergeOptions, mergeConfig)
                            .then((res) => resolve(res))
                            .catch((err) => reject(err))
                        return
                    }
                    if (err.errMsg === 'request:fail abort') {
                        return
                    }
                    if (responseInterceptorsCatchHook && isFunction(responseInterceptorsCatchHook)) {
                        reject(
                            await responseInterceptorsCatchHook(
                                mergeOptions,
                                err
                            )
                        )
                        return
                    }
                    reject(err)
                },
                complete(err: any): void {
                    if (err.errMsg !== 'request:fail abort') {
                        requestCancel.remove(options.url)
                    }
                }
            })
            const { ignoreCancel } = mergeConfig
            !ignoreCancel && requestCancel.add(options.url, requestTask)
        })
    }

    /**
     * 上传文件
     *
     * @param {UploadFileOption} options
     * @param {RequestConfigs} config
     * @author zero
     */
    uploadFile(options: UploadFileOption, config?: Partial<RequestConfigs>) {
        let mergeOptions: RequestOptions = merge(
            {},
            this.reqConfig.requestOptions,
            options
        )

        const mergeConfig: RequestConfigs = merge({}, this.reqConfig, config)
        const {
            requestInterceptorsHook,
            responseInterceptorsHook,
            responseInterceptorsCatchHook
        } = mergeConfig.requestHooks || {}

        if (requestInterceptorsHook && isFunction(requestInterceptorsHook)) {
            mergeOptions = requestInterceptorsHook(mergeOptions, mergeConfig);
        }

        return new Promise((resolve, reject): void => {
            const uploadTask: any = uni.uploadFile({
                ...mergeOptions,
                header: mergeOptions.header,
                success: async (res: UniApp.UploadFileSuccessCallbackResult): Promise<void> => {
                    if (res.statusCode == 200) {
                        res.data = JSON.parse(res.data);
                        if (responseInterceptorsHook && isFunction(responseInterceptorsHook)) {
                            try {
                                res = await responseInterceptorsHook(
                                    res,
                                    mergeConfig,
                                    mergeOptions
                                )
                                resolve(res)
                            } catch (error) {
                                reject(error)
                            }
                            return
                        }
                        resolve(res)
                    } else {
                        reject(res.errMsg)
                    }
                },
                fail(err: UniApp.GeneralCallbackResult): void {
                    if (err.errMsg == 'request:fail abort') {
                        return
                    }
                    if (
                        responseInterceptorsCatchHook &&
                        isFunction(responseInterceptorsCatchHook)
                    ) {
                        reject(responseInterceptorsCatchHook(mergeOptions, err))
                        return
                    }
                    reject(err.errMsg || err)
                }
            });

            uploadTask.onProgressUpdate(({ progress }: { progress: number }): void => {
                mergeConfig.onProgress?.(progress)
            })
        })
    }

    /**
     * SSE流请求
     *
     * @param {RequestOptions} options
     * @param {RequestStreamHooks} hooks
     * @author zero
     */
    eventStream(options: RequestOptions, hooks?: RequestStreamHooks) {
        let mergeOptions: RequestOptions = merge(
            {},
            this.reqConfig.requestOptions,
            options
        )

        const mergeConfigs: RequestStreamHooks = merge(
            {},
            this.reqConfig,
            hooks
        )

        const hook: RequestHooks = mergeConfigs.requestHooks || {}
        const requestInterceptorsHook: any = hook.requestInterceptorsHook
        const responseInterceptorsHook: any = hook.requestInterceptorsHook
        if (requestInterceptorsHook && isFunction(requestInterceptorsHook)) {
            mergeOptions = requestInterceptorsHook(
                mergeOptions,
                mergeConfigs as RequestConfigs
            )
        }

        return new Promise((resolve, reject): void => {
            try {
                // #ifdef H5
                this.__sse_h5(
                    reject,
                    resolve,
                    mergeOptions,
                    mergeConfigs,
                    responseInterceptorsHook
                )
                // #endif

                // #ifdef MP-WEIXIN
                this.__sse_mp(
                    reject,
                    resolve,
                    mergeOptions,
                    mergeConfigs,
                    responseInterceptorsHook
                )
                // #endif

                // #ifdef APP-PLUS
                this.__sse_app(
                    reject,
                    resolve,
                    mergeOptions,
                    mergeConfigs
                )
                // #endif
            } catch (e) {
                reject(e)
            }
        })
    }

    /**
     * H5端流式处理
     *
     * @param {any} reject
     * @param {any} resolve
     * @param {RequestOptions} mergeOptions
     * @param {RequestStreamHooks} mergeConfigs
     * @param {any} responseInterceptorsHook
     * @author zero
     */
    __sse_h5(
        reject: any,
        resolve: any,
        mergeOptions: RequestOptions,
        mergeConfigs: RequestStreamHooks,
        responseInterceptorsHook: any
    ): void {
        const onStart: any = mergeConfigs.onStart
        const onClose: any = mergeConfigs.onClose
        const onMessage: any = mergeConfigs.onMessage
        const push = async (controller: any, reader: any): Promise<void> => {
            try {
                const { value, done } = await reader.read()
                if (done) {
                    controller.close()
                    onClose?.()
                } else {
                    onMessage?.(new TextDecoder().decode(value))
                    controller.enqueue(value)
                    await push(controller, reader)
                }
            } catch (e) {
                console.error(e)
                onClose?.()
            }
        }

        let body: string | undefined = undefined
        let reqUrl: string = String(mergeOptions.url.replace(/^\//, ''))
        if (mergeOptions.method.toUpperCase() === 'GET') {
            reqUrl = `${reqUrl}?${this.__objectToQuery(mergeOptions.params)}`

        }

        if (mergeOptions.method.toUpperCase() === 'POST') {
            body = isObject(mergeOptions.params)
                ? JSON.stringify(mergeOptions.params)
                : (mergeOptions.params as string)
        }

        fetch(reqUrl, {
            ...mergeOptions,
            body,
            headers: {
                'content-type': 'application/json; charset=utf-8',
                ...mergeOptions.header,
                Accept: 'text/event-stream'
            }
        }).then(async (response: any): Promise<any> => {
            if (response.status === 200) {
                if (response.headers.get('content-type')?.includes('text/event-stream')) {
                    const reader = response.body!.getReader()
                    onStart?.(reader)
                    new ReadableStream({
                        start(controller: ReadableStreamDefaultController): void {
                            push(controller, reader)
                        }
                    })
                } else {
                    response._data = await response.json()
                    return response
                }
            } else {
                reject(response.statusText)
            }
        }).then(async (response: any): Promise<void> => {
            if (!response) {
                resolve(response)
            }
            if (responseInterceptorsHook && isFunction(responseInterceptorsHook)) {
                try {
                    response = await responseInterceptorsHook(response, mergeOptions, mergeConfigs)
                    resolve(response)
                } catch (error) {
                    reject(error)
                }
                return
            }
            resolve(response)
        }).catch((error): void => {
            reject(error)
        })
    }

    /**
     * 小程序端流式处理
     *
     * @param {any} reject
     * @param {any} resolve
     * @param {RequestOptions} mergeOptions
     * @param {RequestStreamHooks} mergeConfigs
     * @param {any} responseInterceptorsHook
     * @author zero
     */
    __sse_mp(
        reject: any,
        resolve: any,
        mergeOptions: RequestOptions,
        mergeConfigs: RequestStreamHooks,
        responseInterceptorsHook: any
    ): void {
        const onStart: any = mergeConfigs.onStart
        const onClose: any = mergeConfigs.onClose
        const onMessage: any = mergeConfigs.onMessage

        if (mergeOptions.method.toUpperCase() === 'POST') {
            mergeOptions.data = isObject(mergeOptions.params)
                ? JSON.stringify(mergeOptions.params)
                : (mergeOptions.params as string)
            Reflect.deleteProperty(mergeOptions, 'params')
        }

        let header: Record<string, any> = {}
        const requestTask = uni.request({
            ...mergeOptions,
            enableChunked: true,
            responseType: 'arraybuffer',
            async success(response: any): Promise<void> {
                if (response.statusCode !== 200) {
                    reject(response)
                    return
                }
                resolve(response)
            },
            fail(error: any): void {
                reject(error)
            },
            complete(): void {
                onClose?.()
            }
        })

        onStart?.(requestTask)

        // @ts-ignore
        requestTask.onHeadersReceived((response: any): void => {
            header = response.header
        })

        // @ts-ignore
        requestTask.onChunkReceived(async (response): Promise<void> => {
            const arrayBuffer = response.data
            const uint8Array = new Uint8Array(arrayBuffer)
            const str: string = new TextDecoder('utf-8').decode(uint8Array)
            const contentType = (header?.['Content-Type'] || '').toLowerCase().trim()
            const t: string[] = ['text/event-stream', 'text/event-stream;charset=utf-8']
            if (t.includes(contentType)) {
                onMessage?.(str)
            } else {
                const data = parse(str)
                if (
                    responseInterceptorsHook &&
                    isFunction(responseInterceptorsHook)
                ) {
                    try {
                        const response = await responseInterceptorsHook(
                            { data, statusCode: 200 },
                            mergeConfigs,
                            mergeOptions
                        )
                        resolve(response)
                    } catch (error) {
                        reject(error)
                    }
                }
            }
        })
    }

    /**
     * App端流式处理
     *
     * @param {any} reject
     * @param {any} resolve
     * @param {RequestOptions} mergeOptions
     * @param {RequestStreamHooks} mergeConfigs
     * @author zero
     */
    __sse_app(
        reject: any,
        resolve: any,
        mergeOptions: RequestOptions,
        mergeConfigs: RequestStreamHooks
    ): void {
        const onStart: any = mergeConfigs.onStart
        const onClose: any = mergeConfigs.onClose
        const onMessage: any = mergeConfigs.onMessage

        // @ts-ignore
        const xhr: any = plus?.net?.XMLHttpRequest ? new plus.net.XMLHttpRequest() : null
        if (!xhr) {
            reject({
                data: null,
                statusCode: 0,
                errMsg: 'App端不支持XMLHttpRequest'
            })
            return
        }
        xhr.responseType = 'text'

        // 构建URL
        let url: string = mergeOptions.url
        if (mergeOptions.baseURL) {
            url = mergeOptions.baseURL + (url.startsWith('/') ? url : `/${url}`)
        }

        // 如果是GET请求，添加参数到URL
        if (mergeOptions.method.toUpperCase() === 'GET' && mergeOptions.params) {
            url = `${url}?${this.__objectToQuery(mergeOptions.params)}`
        }

        // 打开连接
        xhr.open(mergeOptions.method, url, true as unknown as string)

        // 设置请求头
        xhr.setRequestHeader('Accept', 'text/event-stream')
        if (mergeOptions.header) {
            Object.keys(mergeOptions.header).forEach(key => {
                xhr.setRequestHeader(key, mergeOptions.header[key])
            })
        }

        // 处理数据接收
        let buffer: string = ''
        xhr.onreadystatechange = function (): void {
            if (xhr.readyState === 3) {
                // 数据接收中
                const responseText = xhr.responseText || ''
                const newData = responseText.substring(buffer.length)
                buffer = responseText
                if (newData) {
                    onMessage?.(newData)
                }
            } else if (xhr.readyState === 4) {
                // 请求完成
                if (xhr.status === 200) {
                    resolve({
                        data: xhr.responseText,
                        statusCode: 200,
                        header: xhr.getAllResponseHeaders()
                    })
                } else {
                    reject({
                        data: xhr.responseText,
                        statusCode: xhr.status,
                        errMsg: 'request:fail'
                    })
                }
                onClose?.()
            }
        }

        // 错误处理
        xhr.onerror = function (): void {
            reject({
                data: null,
                statusCode: 0,
                errMsg: 'request:fail'
            })
            onClose?.()
        }

        // 超时处理
        if (mergeOptions.timeout) {
            xhr.timeout = mergeOptions.timeout
            xhr.ontimeout = function (): void {
                reject({
                    data: null,
                    statusCode: 0,
                    errMsg: 'request:timeout'
                })
                onClose?.()
            }
        }

        // 发送请求
        let body: string | undefined = undefined
        if (mergeOptions.method.toUpperCase() === 'POST' && mergeOptions.body) {
            body = JSON.stringify(mergeOptions.body)
            xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8')
        }

        // 通知开始
        onStart?.(xhr)

        // 发送请求
        xhr.send(body)
    }

    /**
     * 对象格式化为Query语法
     *
     * @param {Record<string, any>} params (参数)
     * @returns {string} Query语法
     * @author zero
     */
    __objectToQuery(params: Record<string, any>): string {
        let query: string = ''
        for (const props of Object.keys(params)) {
            const value = params[props]
            const isEmpty: boolean = !(
                value !== null &&
                value !== '' &&
                typeof value !== 'undefined'
            )
            if (!isEmpty) {
                query += `${props}=${value}&`
            }
        }
        return query.slice(0, -1)
    }
}
