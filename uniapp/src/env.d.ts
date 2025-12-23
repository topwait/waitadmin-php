/// <reference types="vite/client" />

// 声明 .vue 模块的类型 (Vue单文件组件)
declare module '*.vue' {
    import type { DefineComponent } from 'vue'

    const component: DefineComponent<{}, {}, any>
    export default component
}

// 扩展 Vue 组件实例的自定义属性 (适用于 Options API)
declare module 'vue' {
    interface ComponentCustomProperties {
        $t: (key: string, args?: any) => string;
        $go: (
            url: string | number,
            type?: 'navigateTo' | 'redirectTo' | 'reLaunch' | 'switchTab' | 'navigateBack',
            args?: object
        ) => Promise<any> | boolean;
    }
}

// 扩展 Vue 应用实例的类型 (适用于 App 级别)
declare module '@vue/runtime-core' {
    import type { App as _App, AppConfig } from 'vue'
    interface App extends _App {
        mixin: (mixin: object) => void;
        config: AppConfig & {
            globalProperties: Record<string, any>;
        };
    }
}

// 声明全局变量 (通常用于非组件环境的全局方法)
declare global {
    const $t: (key: string, args?: any) => string
    const $go: (
        url: string | number,
        type?: 'navigateTo' | 'redirectTo' | 'reLaunch' | 'switchTab' | 'navigateBack',
        args?: object
    ) => Promise<any> | boolean
    interface Window {
        signLink?: string;
    }
}

export { }
