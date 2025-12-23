import PagesJSON from '@/pages.json'
import useAppStore from '@/stores/app'
import useUserStore from '@/stores/user'
import { storeToRefs } from 'pinia'

export default {
    /**
     * 页面跳转
     * document: https://uniapp.dcloud.net.cn/api/router.html
     *
     * @param { string | number } url
     * @param { string } [type]
     * @param { object } [args]
     * @author zero
     */
    go(
        url: string | number,
        type?: 'navigateTo' | 'redirectTo' | 'reLaunch' | 'switchTab' | 'navigateBack',
        args?: object
    ): Promise<any> | boolean {
        if (type === undefined) {
            const pagePath: string = String(url).replace('/', '')
            const tabBarList: string[] = ['/pages/index/index']
            if (tabBarList.includes(pagePath)) {
                type = 'switchTab'
            } else {
                type = 'navigateTo'
            }
        }

        switch (type) {
            // 保留当前页面: 跳转到应用内的某个页面 (uni.navigateBack可以返回到原页面)
            case 'navigateTo':
                return uni.navigateTo({
                    url: String(url),
                    ...args || {}
                })
            // 关闭当前页面: 跳转到应用内的某个页面
            case 'redirectTo':
                return uni.redirectTo({
                    url: String(url),
                    ...args || {}
                })
            // 关闭所有页面: 打开到应用内的某个页面
            case 'reLaunch':
                return uni.reLaunch({
                    url: String(url),
                    ...args || {}
                })
            // 跳转到tabBar页面: 并关闭其他所有非tabBar页面
            case 'switchTab':
                return uni.switchTab({
                    url: String(url),
                    ...args || {}
                })
            // 关闭当前页面: 返回上一页面或多级页面
            case 'navigateBack':
                return uni.navigateBack({
                    delta: parseInt(String(url || '1')),
                    ...args || {}
                })
            default:
                uni.showToast({
                    title: '$go() Errors',
                    icon: 'error',
                    duration: 2000
                }).then((): void => { })
                return false
        }
    },
    /**
     * 页面跳转前拦截
     */
    intercept(): void {
        const list: string[] = ['navigateTo', 'redirectTo', 'reLaunch', 'switchTab', 'navigateBack']
        list.forEach((item: string): void => {
            uni.addInterceptor(item, {
                invoke(route): boolean | any {
                    // 返回页面处理
                    if (item === 'navigateBack') {
                        return true
                    }

                    // 地址为空处理
                    if (!route.url) {
                        return false
                    }

                    // 当前地址处理
                    let url: string = route.url.split('?')[0]
                    if (url === '/') {
                        url = '/pages/index/index'
                    }

                    // 当前访问路由
                    const appStore = useAppStore()
                    const pageRoutes: RouteType[] = appStore.getRoutePages()
                    const currentRoute: RouteType | any = pageRoutes.find(
                        (item: RouteType): boolean => {
                            return url === item.path
                        }
                    )

                    // 页面不存在时
                    if (!currentRoute) {
                        uni.reLaunch({
                            url: '/pages/index/404'
                        }).then((): void => { })
                        return false
                    }

                    // 获取用户状态
                    const userStore = useUserStore()
                    const { isLogin } = storeToRefs(userStore)

                    // 登录页面拦截
                    if (currentRoute?.login && !isLogin.value) {
                        uni.navigateTo({
                            url: '/pages/login/index'
                        }).then((): void => { })
                        return false
                    }

                    return route
                },
                fail(err): void {
                    throw new Error(err)
                }
            })
        })
    }
}

/**
 * 路由参数类型
 */
export interface RouteType {
    login?: boolean;
    theme?: boolean;
    path: string;
    [key: string]: any;
}

/**
 * 页面配置类型
 */
export interface PagesType {
    pages: RouteType[];
    subPackages?: {
        root: string;
        pages: RouteType[];
    }[];
}

/**
 * 提取路由页面
 */
export function getRoutes(): RouteType[] {
    const mainsPages: RouteType[] = __getPagesRoutes(PagesJSON.pages)
    const subPackage: RouteType[] = __getSubPackagesRoutes(PagesJSON)
    return mainsPages.concat(subPackage)
}

/**
 * 提取主包页面
 */
function __getPagesRoutes(pages: RouteType[], root?: string): RouteType[] {
    const includes: string[] = ['theme', 'login', 'path']
    const routes: RouteType[] = []
    for (let i: number = 0; i < pages.length; i++) {
        const item: RouteType | any = pages[i]
        const route: RouteType = {} as RouteType
        for (let j: number = 0; j < includes.length; j++) {
            const key: string = includes[j] || ''
            const val: string | boolean | undefined = item[key]
            if (key === 'path') {
                const pagePath: string = String(root ? `${root}/${val}` : val)
                route[key] = `/${pagePath}`
                continue
            }
            if (val !== undefined) {
                route[key] = val
            }
        }
        routes.push(route)
    }
    return routes
}

/**
 * 提取子包页面
 */
function __getSubPackagesRoutes(pagesJson: PagesType): RouteType[] {
    const { subPackages = [] } = pagesJson
    return subPackages.flatMap(subPackage =>
        __getPagesRoutes(subPackage.pages, subPackage.root)
    )
}
