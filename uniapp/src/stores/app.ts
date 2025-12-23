import { defineStore } from 'pinia'
import { getRoutes, type RouteType } from '@/utils/request/router'
import indexApi from '@/api/index/index'

interface AppSate {
    // 页面路由
    pagesJson: RouteType[],
    // 暗黑主题
    darkTheme: boolean;
    // 当前标签
    curTabBar: number;
    // 底部导航
    tabbar: string[];
    // 系统配置
    config: SysConfigResponse;
    // 装修数据
    diyDto: DecorateResponse;
}

const useAppStore = defineStore('appStore', {
    state: (): AppSate => ({
        pagesJson: [],
        darkTheme: false,
        curTabBar: 0,
        tabbar: [],
        config: {} as SysConfigResponse,
        diyDto: {} as DecorateResponse
    }),
    getters: {
        ossDomain: (state) => state.config?.oss_domain || '',
        getH5Config: (state) => state.config.h5 || {},
        getLoginConfig: (state) => state.config.login || {}
    },
    actions: {
        /**
         * 补全文件地址
         */
        toAbsoluteUrl(url: string): string {
            return url ? `${this.ossDomain}/${url}` : ''
        },
        /**
         * 获取所有页面
         */
        getRoutePages(): RouteType[] {
            if (this.pagesJson.length === 0) {
                this.pagesJson = getRoutes()
            }
            return this.pagesJson
        },
        /**
         * 获取系统配置
         */
        async getSysConfig(): Promise<SysConfigResponse> {
            this.config = await indexApi.config()
            return this.config
        },
        /**
         * 获取装修数据
         */
        async getDecorates(): Promise<DecorateResponse> {
            this.diyDto = await indexApi.decorate()
            this.tabbar = this.diyDto?.tabbar.routes
            return this.diyDto
        },
        /**
         * 设置当前导航
         */
        setCurrentTabBar(index: number): void {
            this.curTabBar = index
        },
        /**
         * H5端关闭拦截
         */
        async h5Intercepts(): Promise<void> {
            // #ifdef H5
            const { status, close_url } = this.getH5Config
            if (!status) {
                if (close_url) {
                    location.href = close_url
                    return
                }
                await uni.reLaunch({
                    url: '/pages/empty/empty'
                })
            }
            // #endif
        }
    }
})

export default useAppStore
