import { useAppStore } from '@/stores/appStore'
import PagesJSON from '@/pages.json'
import toolUtil from '@/utils/toolUtil'

export default {
    onLoad() {
        toolUtil.setTabBar()
    },
    computed: {
        themeName() {
            return this.initTheme()
        },
        themeColor() {
            const appStore = useAppStore()
            return appStore.themeConfigVal.color
        }
    },
    methods: {
        // 初始化主题
        initTheme() {
            const appStore = useAppStore()
            const routes = getCurrentPages()
            const currentRoute = routes[routes.length - 1].route

            const themeName = appStore.themeConfigVal.subject
            const themeColor = appStore.themeConfigVal.color

            if (themeName) {
                if (currentRoute.startsWith('pages/')) {
                    this.__changeTheme(PagesJSON.pages, currentRoute, '', themeColor)
                } else {
                    const { subPackages } = PagesJSON
                    for (let i = 0; i < subPackages.length; i++) {
                        const subRoots = subPackages[i].root
                        const currRoot = currentRoute.split('/')[0].toLowerCase()
                        if (!subRoots || subRoots.toLowerCase() !== currRoot) {
                            continue
                        }

                        const subPages = subPackages[i].pages
                        for (let i = 0; i < subPages.length; i++) {
                            this.__changeTheme(subPages, currentRoute, subRoots, themeColor)
                        }
                    }
                }
            }

            return themeName
        },
        // 改变主题色
        __changeTheme(pages, currentRoute, subRoot, themeColor) {
            for (let i = 0; i < pages.length; i++) {
                let paths = pages[i].path
                if (subRoot) {
                    paths = subRoot + '/' + paths
                }
                if (paths === currentRoute) {
                    if (pages[i].vary !== false) {
                        uni.setNavigationBarColor({
                            frontColor: '#ffffff',
                            backgroundColor: themeColor
                        })
                    }
                    break
                }
            }
        }
    }
}
