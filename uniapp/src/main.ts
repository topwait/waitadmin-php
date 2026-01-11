import type { Pinia } from 'pinia'
import { createSSRApp } from 'vue'
import { createPinia } from 'pinia'
import { createI18n } from 'vue-i18n'
import App from './App.vue'
import 'default-passive-events'
import './assets/styles/index.scss'
import appMixin from '@/mixins/appMixin'
import router from '@/utils/request/router'
import en from './lang/en.json'
import zhHans from './lang/zh-Hans.json'
import zhHant from './lang/zh-Hant.json'

const i18nConfig = {
    legacy: false,
    globalInjection: true,
    locale: uni.getLocale(),
    messages: {
        'en': en, // 英文
        'zh-Hans': zhHans, // 简体中文
        'zh-Hant': zhHant  // 繁体中文
    }
}

const patchVueAssignSlots = () => {
    const originalDefineProperty = Object.defineProperty
    Object.defineProperty = function(
        obj: any, 
        prop: string | symbol,
        descriptor: PropertyDescriptor
    ): any {
        if (prop === '_' && descriptor && descriptor.writable === false) {
            descriptor.writable = true
        }
        return originalDefineProperty(obj, prop, descriptor)
    }
}

patchVueAssignSlots()

export function createApp() {
    const pinia: Pinia = createPinia()
    const app = createSSRApp(App)
    const i18n = createI18n(i18nConfig)

    app.use(i18n)
    app.use(pinia)
    app.mixin(appMixin)
    app.config.globalProperties.$go = router.go
    router.intercept()

    return {
        app
    }
}
