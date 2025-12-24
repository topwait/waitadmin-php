const config = {
    // 版本编号
    version: '2.0.1',
    // 请求域名
    baseUrl: `${import.meta.env.VITE_APP_BASE_URL || ''}`,
    // 请求前缀
    urlPrefix: '/api',
    // 请求超时
    timeout: 10 * 1000,
    // 请求重试次数
    retryCount: 2,
    // 请求重试间隔(ms)
    retryTimeout: 1000
}

export default config
