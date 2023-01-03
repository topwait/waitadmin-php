import { config } from './config.js'

const install = (Vue) => {

    // 参数配置
    Vue.config.globalProperties.$u.http.setConfig(config)

    // 请求拦截配置
    Vue.config.globalProperties.$u.http.interceptor.request = (config) => {
        console.log('请求拦截')
        return config
    }

    // 响应拦截配置
    Vue.config.globalProperties.$u.http.interceptor.response = (res) => {
        console.log('响应拦截')
        return res
    }
}

export default {
    install
}
