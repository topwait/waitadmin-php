import { config } from './config'
import { useUserStore } from '@/stores/userStore'
import errorEnum from '@/enums/errorEnum'
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
    Vue.config.globalProperties.$u.http.interceptor.response = (response) => {
        const result = response.data
        const { logout } = useUserStore()

        const isStructure = response.header.structure || response.header.Structure
        switch (result.code) {
            case errorEnum.SUCCESS:
                return isStructure ? result : result.data
            case errorEnum.SYSTEM_ERROR:
            case errorEnum.PARAMS_ERROR:
            case errorEnum.METHOD_ERROR:
            case errorEnum.CONTROL_ERROR:
            case errorEnum.REQUEST_ERROR:
            case errorEnum.OPERATE_ERROR:
            case errorEnum.UPLOADS_ERROR:
            case errorEnum.PURVIEW_ERROR:
                uni.$u.toast(result.msg)
                return Promise.reject(result)
            case errorEnum.LOGIN_EMPTY_ERROR:
            case errorEnum.LOGIN_EXPIRE_ERROR:
                logout()
                uni.navigateTo({ url: '/pages/login/enroll' })
                return Promise.reject(result)
            default:
                return result
        }
    }
}

export default {
    install
}
