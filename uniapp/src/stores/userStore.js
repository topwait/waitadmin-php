import { defineStore } from 'pinia'
import { userInfoApi } from '@/api/usersApi.js'
import cacheEnum from '@/enums/cacheEnum'
import cacheUtil from '@/utils/cacheUtil'

export const useUserStore = defineStore({
    id: 'userStore',
    state: () => {
        return {
            token: cacheUtil.get(cacheEnum.TOKEN_KEY),
            userInfo: {}
        }
    },
    getters: {
        isLogin: (state) => !!state.token
    },
    actions: {
        async getUserInfo() {
            if (this.isLogin) {
                const result = await userInfoApi()
                this.userInfo = result.data
            }
        },
        login(token) {
            this.token = token
            cacheUtil.set(cacheEnum.TOKEN_KEY, token)
        },
        logout() {
            this.token = ''
            cacheUtil.remove(cacheEnum.TOKEN_KEY)
        }
    }
})
