import { useAppStore } from '@/stores/appStore'
import PagesJSON from '@/pages.json'

export default {
    data() {
        return {
            // 主题名称
            themeName: ''
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

            this.themeName = appStore.themeConfigVal.subject
            if (this.themeName) {
                for (let i = 0; i < PagesJSON.pages.length; i++) {
                    const paths = PagesJSON.pages[i].path
                    if (paths === currentRoute) {
                        const style = PagesJSON.pages[i].style
                        const navigationBarFontsTextStyle  = (style || []).navigationBarTextStyle
                        const navigationBarBackgroundColor = (style || []).navigationBarBackgroundColor
                        if (style && (navigationBarFontsTextStyle !== 'black' && navigationBarBackgroundColor !== '#ffffff')) {
                            uni.setNavigationBarColor({
                                frontColor: appStore.themeConfigVal.frontColor,
                                backgroundColor: appStore.themeConfigVal.backgroundColor
                            })
                        }
                        break
                    }
                }
            }
        }
    }
}
