import { defineStore } from 'pinia'
import { getSysConfigApi } from '@/api/indexApi'

export const useAppStore = defineStore({
    id: 'appStore',
    state: () => {
        return {
            config: {
                login: {
                    force_mobile: 0,
                    login_modes: [],
                    login_other: []
                }
            }
        }
    },
    getters: {
        loginConfigVal: (state) => state.config.login || {}
    },
    actions: {
        async getSysConfig() {
            const result = await getSysConfigApi()
            this.config = result.data
        }
    }
})
