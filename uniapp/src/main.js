import { createSSRApp } from 'vue'
import { createPinia } from 'pinia'
import { createI18n } from 'vue-i18n'
import App from './App.vue'
import route from '@/utils/request/route'
import uHttp from '@/utils/request/http'
import uView from '@/uni_modules/vk-uview-ui'
import minxinsApp from '@/mixins/appMixin'
import 'default-passive-events'
import './styles/index.scss'
import en from './lang/en.json'
import zhHans from './lang/zh-Hans.json'
import zhHant from './lang/zh-Hant.json'

let i18nConfig = {
    legacy: false,
    locale: uni.getLocale(),
    messages: {
        'en': en,
        'zh-Hans': zhHans,
        'zh-Hant': zhHant
    }
}

export function createApp() {
    const pinia = createPinia()
    const app = createSSRApp(App)
    const i18n = createI18n(i18nConfig)

    app.config.globalProperties.$onLaunched = new Promise(resolve => {
        app.config.globalProperties.$isResolve = resolve
    })

    app.config.globalProperties.$go = route.go
    app.config.globalProperties.$sleep = route.sleep
    app.use(pinia)
    app.use(uView)
    app.use(uHttp)
    app.use(i18n)
    app.mixin(minxinsApp)
    route.intercept()

    return {
        app
    }
}
