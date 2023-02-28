import { defineStore } from 'pinia'
import { getSysConfigApi } from '@/api/indexApi'

export const useAppStore = defineStore({
    id: 'appStore',
    state: () => {
        return {
            config: {
                h5: {
                    status: 0,
                    close_url: ''
                },
                login: {
                    force_mobile: 0,
                    login_modes: [],
                    login_other: []
                }
            }
        }
    },
    getters: {
        h5ConfigVal: (state) => state.config.h5 || {},
        loginConfigVal: (state) => state.config.login || {}
    },
    actions: {
        async getSysConfig() {
            this.config = await getSysConfigApi()
        },
        h5Intercepts() {
            // #ifdef H5
            const { status, close_url } = this.h5ConfigVal
            if (status === 1) {
                if (close_url) return (location.href = close_url)
                uni.reLaunch({ url: '/pages/empty/empty' })
            }
            // #endif
        }
    }
})
