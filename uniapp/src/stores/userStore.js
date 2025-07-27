import { defineStore } from 'pinia'
import userApi from '@/api/userApi'
import loginApi from '@/api/loginApi'
import cacheEnum from '@/enums/cacheEnum'
import cacheUtil from '@/utils/cacheUtil'

export const useUserStore = defineStore('userStore', {
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
            this.userInfo = await userApi.info()
            if (!this.userInfo?.id) {
                this.logout()
            }
        },
        login(token) {
            this.token = token
            cacheUtil.set(cacheEnum.TOKEN_KEY, token)
        },
        logout() {
            this.token = ''
            cacheUtil.remove(cacheEnum.TOKEN_KEY)
            loginApi.logout()
        }
    }
})
