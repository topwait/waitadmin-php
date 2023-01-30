import { createSSRApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import uHttp from '@/utils/request/http.js'
import uView from '@/uni_modules/vk-uview-ui'
import './styles/index.scss'

export function createApp() {
    const pinia = createPinia()
    const app = createSSRApp(App)
    app.use(pinia)
    app.use(uView)
    app.use(uHttp)
    return {
        app
    }
}
