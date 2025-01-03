import { computed } from 'vue'
import { useAppStore } from '@/stores/appStore'
import { useUserStore } from '@/stores/userStore'
import clientUtil from '@/utils/clientUtil'
import cacheUtil from '@/utils/cacheUtil'
import cacheEnum from '@/enums/cacheEnum'

export default {
    /**
     * 提取微信编码
     */
    obtainWxCode() {
        return new Promise((resolve, reject) => {
            uni.login({
                success(response) {
                    if ('login:ok' === response.errMsg) {
                        resolve(response.code)
                    } else {
                        reject(response.errMsg)
                    }
                },
                fail(error) {
                    reject(error)
                }
            })
        })
    },

    /**
     * 提取微信位置
     */
    obtainWxLocation() {
        return new Promise((resolve, reject) => {
            uni.getLocation({
                type: 'wgs84',
                success: function (response) {
                    resolve(response)
                },
                fail(error) {
                    reject(error)
                }
            })
        })
    },

    /**
     * 获取底部导航
     */
    tarBarList() {
        return [
            'pages/index/index',
            'pages/article/list',
            'pages/user/home'
        ]
    },

    /**
     * 获取当前页面
     */
    currentPage() {
        const pages = getCurrentPages()
        const currentPage = pages[pages.length - 1]
        return currentPage || {}
    },

    /**
     * 页面浏览足迹
     * @param {boolean} write (true=写入足迹,false=返回上一次访问页面)
     * @param {boolean} all   (是否返回所有足迹路径)
     * @returns {string|array}
     */
    walksPage(write = false, all = false) {
        let footrint = cacheUtil.get(cacheEnum.FOOTPRINT) || []

        if (write === true) {
            let pageRoute = this.currentPage().route
            footrint.push(pageRoute)
            footrint = footrint.slice(-5)
            cacheUtil.set(cacheEnum.FOOTPRINT, footrint)
            return pageRoute
        }

        if (all) {
            return footrint
        }

        return footrint[0] || ''
    },

    /**
     * 上传文件资源
     * @param {string} filePath 本地文件路径
     * @param {string} fileType 文件类型: image/video/audio
     * @param {string} type     上传类型: picture/video/document/package
     * @param {string} scene    上传场景: permanent=永远存储,temporary=临时存储
     */
    uploadFile(filePath, fileType, type, scene = 'permanent') {
        const userStore = useUserStore()
        const token = userStore.$state.token
        const terminal = clientUtil.fetchClient()

        return new Promise((resolve, reject) => {
            uni.uploadFile({
                name: 'file',
                url: `${import.meta.env.VITE_APP_BASE_URL}/api/upload/${scene}`,
                filePath: filePath,
                fileType: fileType,
                header: { 'token': token, 'terminal': terminal },
                formData: { 'type': type },
                success: res => {
                    let result = JSON.parse(res.data)
                    if (result.code === 0) {
                        resolve(result.data)
                    } else {
                        reject()
                    }
                },
                fail: (err) => {
                    reject(err)
                }
            })
        })
    },

    /**
     * 渲染底部导航
     */
    setTabBar() {
        const currUrl = this.currentPage().route
        const mainUrl = this.tarBarList()
        if (currUrl && !mainUrl.includes(currUrl)) {
            return
        }

        const appStore = useAppStore()
        const diyBottomStyle = computed(() => appStore.tabBarConfigVal?.style)
        const diyBottomNav = computed(() => appStore.tabBarConfigVal?.list)

        // 设置导航文本颜色
        if (diyBottomStyle.value?.selectedColor) {
            uni.setTabBarStyle({
                color: diyBottomStyle.value.unselectedColor,
                selectedColor: diyBottomStyle.value.selectedColor
            })
        }

        // APP端导航渲染
        // #ifdef APP-PLUS
        diyBottomNav.value?.forEach((item, index) => {
            uni.downloadFile({
                url: item.iconPath,
                success: res => {
                    uni.setTabBarItem({
                        index,
                        text: item.text,
                        iconPath: res.tempFilePath,
                    })
                }
            })
            uni.downloadFile({
                url: item.selectedIconPath,
                success: res => {
                    uni.setTabBarItem({
                        index,
                        text: item.text,
                        selectedIconPath: res.tempFilePath,
                    })
                }
            })
        })
        // #endif

        // 非APP端导航渲染
        // #ifndef APP-PLUS
        diyBottomNav.value?.forEach((item, index) => {
            uni.setTabBarItem({
                index,
                text: item.text,
                iconPath: item.iconPath,
                selectedIconPath: item.selectedIconPath
            })
        })
        // #endif

        // 显示底部导航栏
        uni.showTabBar()
    }
}
