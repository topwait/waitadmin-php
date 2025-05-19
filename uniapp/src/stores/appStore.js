import { defineStore } from 'pinia'
import IndexApi from '@/api/indexApi'
import toolUtil from '@/utils/toolUtil'

export const useAppStore = defineStore({
    id: 'appStore',
    state: () => {
        return {
            config: {
                h5: {},
                login: {},
                theme: {},
                tabBar: []
            }
        }
    },
    getters: {
        h5ConfigVal: (state) => state.config.h5 || {},
        loginConfigVal: (state) => state.config.login || {},
        themeConfigVal: (state) => state.config.theme || {},
        tabBarConfigVal: (state) => state.config.tabBar || []
    },
    actions: {
        async getSysConfig() {
            this.config = await IndexApi.config()
            await toolUtil.setTabBar()
        },
        h5Intercepts() {
            // #ifdef H5
            const { status, close_url } = this.h5ConfigVal
            if (status === 0) {
                if (close_url) {
                    location.href = close_url
                    return
                }
                uni.reLaunch({ url: '/pages/empty/empty' })
            }
            // #endif
        }
    }
})
