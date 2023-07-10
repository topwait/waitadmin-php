import { createSSRApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import route from '@/utils/request/route'
import uHttp from '@/utils/request/http'
import uView from '@/uni_modules/vk-uview-ui'
import minxinsApp from '@/mixins/appMixin'
import './styles/index.scss'

export function createApp() {
    const pinia = createPinia()
    const app = createSSRApp(App)

    app.config.globalProperties.$onLaunched = new Promise(resolve => { 
        app.config.globalProperties.$isResolve = resolve
    })

    app.config.globalProperties.$go = route.go
    app.config.globalProperties.$sleep = route.sleep
    app.use(pinia)
    app.use(uView)
    app.use(uHttp)
    app.mixin(minxinsApp)
    route.intercept()

    return {
        app
    }
}
