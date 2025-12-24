import { defineStore } from 'pinia'
import { CacheEnum } from '@/enums/cache'
import cacheUtil from '@/utils/cache'
import loginApi from '@/api/login'
import userApi from '@/api/user'

interface UserSate {
    token: string | null;
    userInfo: UserCenterResponse;
}

const useUserStore = defineStore('userStore', {
    state: (): UserSate => {
        return {
            token: cacheUtil.get(CacheEnum.TOKEN_KEY),
            userInfo: {} as UserCenterResponse
        }
    },
    getters: {
        isLogin: (state: UserSate) => !!state.token,
        getUserInfo: (state: UserSate) => state.userInfo
    },
    actions: {
        /**
         * 用户信息
         */
        async getUser(): Promise<void> {
            if (this.isLogin) {
                this.userInfo = await userApi.center()
                if (!this.userInfo?.id) {
                    this.logout()
                }
            }
        },
        /**
         * 登录系统
         */
        async login(token: string): Promise<void> {
            this.token = token
            await this.getUser()
            cacheUtil.set(CacheEnum.TOKEN_KEY, token)
        },
        /**
         * 退出登录
         */
        logout(): void {
            this.token = null
            cacheUtil.remove(CacheEnum.TOKEN_KEY)
            loginApi.logout().then()
        }
    }
})

export default useUserStore
