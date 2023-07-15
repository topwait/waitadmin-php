export const config = {
    // 请求的本域名
    baseUrl: `${import.meta.env.VITE_APP_BASE_URL || ''}/api`,
    // 设置为JSON
    dataType: 'json',
    // 显示请求中
    showLoading: false,
    // 加载提示文
    loadingText: '请求中...',
    // 在此时间内请求中就显示加载中的动画
    loadingTime: 800,
    // 是否在拦截器中返回服务端的原始数据
    originalData: true,
    // 展示加载时候给透明蒙层防止触摸穿透
    loadingMask: true,
    // 配置网络发起的请求头信息
    header: {
        'content-type': 'application/json;charset=UTF-8'
    }
}
