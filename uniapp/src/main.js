import { createSSRApp } from 'vue'
import App from './App.vue'
import uHttp from '@/utils/request/http.js'
import uView from '@/uni_modules/vk-uview-ui'
import './styles/index.scss'

export function createApp() {
    const app = createSSRApp(App)
    app.use(uView)
    app.use(uHttp)
    return {
        app
    }
}
