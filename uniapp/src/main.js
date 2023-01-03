import { createSSRApp } from 'vue'
import App from './App.vue'
import uView from '@/uni_modules/vk-uview-ui'
import '@/uni_modules/vk-uview-ui/index.scss'

export function createApp() {
    const app = createSSRApp(App)
    app.use(uView)
    return {
        app
    }
}
