import { defineStore } from 'pinia'
import { getSysConfigApi } from '@/api/indexApi'

export const useAppStore = defineStore({
    id:  'appStore',
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
        getLoginConfig: (state) => state.config.login || {}
    },
    actions: {
        async getSysConfig() {
            const data = await getSysConfigApi()
            this.config = data.data
        }
    }
})
