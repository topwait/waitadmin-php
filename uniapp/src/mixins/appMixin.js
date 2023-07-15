import { useAppStore } from '@/stores/appStore'
import PagesJSON from '@/pages.json'

export default {
    data() {
        return {
            // 主题名称
            themeName: '',
            // 主题颜色
            themeColor: ''
        }
    },
    async onLoad() {
        await this.$onLaunched
        await this.initTheme()
    },
    methods: {
        // 初始化主题
        initTheme() {
            const appStore = useAppStore()
            const routes = getCurrentPages()
            const currentRoute = routes[routes.length - 1].route

            this.themeName = appStore.themeConfigVal.subject || 'default-theme'
            this.themeColor = appStore.themeConfigVal.color || '#2979ff'

            if (this.themeName) {
                if (currentRoute.startsWith('pages/')) {
                    this.__changeTheme(PagesJSON.pages, currentRoute)
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
                            this.__changeTheme(subPages, currentRoute, subRoots)
                        }
                    }
                }
            }
        },
        // 改变主题色
        __changeTheme(pages, currentRoute, subRoot) {
            for (let i = 0; i < pages.length; i++) {
                let paths = pages[i].path
                if (subRoot) {
                    paths = subRoot + '/' + paths
                }
                if (paths === currentRoute) {
                    if (pages[i].vary !== false) {
                        uni.setNavigationBarColor({
                            frontColor: '#ffffff',
                            backgroundColor: this.themeColor
                        })
                    }
                    break
                }
            }
        }
    }
}
