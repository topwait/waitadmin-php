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
        initTheme() {
            const appStore = useAppStore()
            const routes = getCurrentPages()
            const currentRoute = routes[routes.length - 1].route

            this.themeName = appStore.themeConfigVal.subject || 'default-theme'
            this.themeColor = appStore.themeConfigVal.color || '#2979ff'

            if (this.themeName) {
                for (let i = 0; i < PagesJSON.pages.length; i++) {
                    const paths = PagesJSON.pages[i].path
                    if (paths === currentRoute) {
                        const style = PagesJSON.pages[i].style || []
                        if (style.navigationRetinueTheme !== false) {
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
}
