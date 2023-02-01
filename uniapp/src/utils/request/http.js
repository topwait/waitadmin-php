import { config } from './config'
import { useUserStore } from '@/stores/userStore'
import clientUtil from '@/utils/clientUtil'

const install = (Vue) => {
    // 参数配置
    Vue.config.globalProperties.$u.http.setConfig(config)

    // 请求拦截配置
    Vue.config.globalProperties.$u.http.interceptor.request = (config) => {
        const userStore = useUserStore()
        config.header.token = userStore.$state.token
        config.header.terminal = clientUtil.fetchClient()
        return config
    }

    // 响应拦截配置
    Vue.config.globalProperties.$u.http.interceptor.response = (res) => {
        return res
    }
}

export default {
    install
}
