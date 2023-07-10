import { useAppStore } from '@/stores/appStore'

export default {
    data() {
        return {
            themeName: 'light-theme'
        }
    },
    onLoad() {
        const appStore = useAppStore()
        console.log(appStore.themeConfigVal)
    }
}