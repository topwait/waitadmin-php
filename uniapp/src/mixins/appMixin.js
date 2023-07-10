import { useAppStore } from '@/stores/appStore'

export default {
    data() {
        return {
            // 主题名称
            themeName: ''
        }
    },
    async onLoad() {
        await this.$onLaunched
        this.initTheme()
    },
    methods: {
        initTheme() {
            const appStore = useAppStore()
            this.themeName = appStore.themeConfigVal.subject
            if (this.themeName) {
                uni.setNavigationBarColor({
                    frontColor: appStore.themeConfigVal.frontColor,
                    backgroundColor: appStore.themeConfigVal.backgroundColor,
                })
            }
        }
    }
}
