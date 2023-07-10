import { useAppStore } from '@/stores/appStore'
import { initTheme } from '@dcloudio/uni-cli-shared'

export default {
    data() {
        return {
            // 主题名称
            themeName: ''
        }
    },
    async onLoad() {
        await this.$onLaunched
        initTheme()
    },
    methods: {
        initTheme() {
            const appStore = useAppStore()
            this.themeName = appStore.themeConfigVal.subject
            if (this.themeName) {
                uni.setNavigationBarColor({
                    frontColor: appStore.themeConfigVal.frontColor      || 'white',
                    frontColor: appStore.themeConfigVal.backgroundColor || '#2979ff',
                })
            }
        }
    }
}
