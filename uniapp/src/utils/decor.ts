import useAppStore from '@/stores/app'

const decorUtil = {
    /**
     * 当前所在页面
     */
    currentPage() {
        const pages = getCurrentPages()
        const currentPage = pages[pages.length - 1]
        return currentPage || {}
    },

    /**
     * 设置顶部导航栏
     *
     * @returns {Promise<void>}
     * @author zero
     */
    async setNavBar(): Promise<void> {
        const appStore = useAppStore()
        const pagesJson = appStore.pagesJson
        const currPage: string = `/${this.currentPage().route || ''}`
        for (let i: number = 0; i < pagesJson.length; i++) {
            const item: any = pagesJson[i]
            if (item.path === currPage) {
                if (item?.theme === true) {
                    const color: string = appStore.diyDto?.color || '#4d80f0'
                    setTimeout((): void => {
                        uni.setNavigationBarColor({
                            frontColor: '#ffffff',
                            backgroundColor: color
                        })
                    }, 20)
                }
                break
            }
        }
    },

    /**
     * 设置底部导航栏
     *
     * @returns {Promise<void>}
     * @author zero
     */
    async setTabBar(): Promise<void> {
        // 导航栏装修数据
        const appStore = useAppStore()
        const data: DecorateResponse['tabbar'] = appStore.diyDto.tabbar
        if (!data) {
            return
        }

        // 非导航页面退出
        const currPage: string = `/${this.currentPage().route || ''}`
        if (!appStore.tabbar.includes(currPage)) {
            return
        }

        // 隐藏系统固定导航 (APP端处理)
        // #ifdef APP-PLUS
        if (data.style.effect === 'custom') {
            await uni.hideTabBar()
        }
        // #endif

        // 隐藏系统固定导航 (非APP端处理)
        // #ifndef APP-PLUS
        await uni.hideTabBar()
        // #endif

        // 设置导航样式风格
        await uni.setTabBarStyle({
            color: data.style.color,
            selectedColor: data.style.selectedColor,
            backgroundColor: data.style.backgroundColor
        })

        // 是APP端导航渲染
        // #ifdef APP-PLUS
        data.list?.forEach((item: any, index: number): void => {
            uni.downloadFile({
                url: item.iconPath,
                success: (res: UniApp.DownloadSuccessData): void => {
                    uni.setTabBarItem({
                        index,
                        text: item.text,
                        iconPath: res.tempFilePath
                    })
                }
            })
            uni.downloadFile({
                url: item.selectedIconPath,
                success: (res: UniApp.DownloadSuccessData): void => {
                    uni.setTabBarItem({
                        index,
                        text: item.text,
                        selectedIconPath: res.tempFilePath
                    })
                }
            })
        })
        // #endif

        // 非APP端导航渲染
        // #ifndef APP-PLUS
        data.list?.forEach((item: any, index: number): void => {
            uni.setTabBarItem({
                index,
                text: item.text,
                pagePath: item.pagePath,
                iconPath: item.iconPath,
                selectedIconPath: item.selectedIconPath
            })
        })
        // #endif

        // 最终显示导航栏
        if (data.style.effect !== 'custom') {
            await uni.showTabBar()
        }
    }
}

export default decorUtil
