import { defineStore } from 'pinia'
import UserApi from '@/api/UserApi'
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
                this.userInfo = await UserApi.info()
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
