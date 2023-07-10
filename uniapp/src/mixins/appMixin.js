import { useAppStore } from '@/stores/appStore'

export default {
    data() {
        return {
            themeName: 'light-theme',
            themeStyle: 'white',
            themeBgColor: ''
        }
    },
    async onLoad() {
        await this.$onLaunched
        const appStore = useAppStore()

        //this.themeName = appStore.themeConfigVal.subject
        //this.textStyle = appStore.themeConfigVal.textStyle
        //this.bgColor = appStore.themeConfigVal.bgColor
    }
}
