import { createSSRApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import route from '@/utils/request/route'
import uHttp from '@/utils/request/http'
import uView from '@/uni_modules/vk-uview-ui'
import './styles/index.scss'

export function createApp() {
    const pinia = createPinia()
    const app = createSSRApp(App)
    app.config.globalProperties.$go = route.go
    app.config.globalProperties.$sleep = route.sleep
    app.use(pinia)
    app.use(uView)
    app.use(uHttp)
    route.intercept()

    return {
        app
    }
}
