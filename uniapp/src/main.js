import { createSSRApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import uGo from '@/utils/request/go'
import uHttp from '@/utils/request/http'
import uView from '@/uni_modules/vk-uview-ui'
import './styles/index.scss'

export function createApp() {
    const pinia = createPinia()
    const app = createSSRApp(App)
    app.config.globalProperties.$go = uGo.init
    app.use(pinia)
    app.use(uView)
    app.use(uHttp)
    
    return {
        app
    }
}
