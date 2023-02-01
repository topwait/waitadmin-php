import { defineStore } from 'pinia'
import cacheEnum from '@/enums/cacheEnum'
import cacheUtil from '@/utils/cacheUtil'

export const useUserStore = defineStore({
    id: 'userStore',
    state: () => {
        return {
            token: cacheUtil.get(cacheEnum.TOKEN_KEY)
        }
    },
    getters: {
        isLogin: (state) => !!state.token
    },
    actions: {
        login(token) {
            this.token = token
            cacheUtil.set('token', token)
        },
        logout() {
            this.token = ''
            cacheUtil.remove('token')
        }
    }
})
