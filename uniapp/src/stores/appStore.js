import { defineStore } from 'pinia'
import { getSysConfigApi } from '@/api/indexApi'

export const useAppStore = defineStore({
    id: 'appStore',
    state: () => {
        return {
            config: {
                login: {
                    forceMobile: 0,
                    loginModes: [],
                    loginOther: []
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
