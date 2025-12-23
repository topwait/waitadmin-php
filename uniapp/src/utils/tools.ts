import { cloneDeep } from 'lodash-es'
import useAppStore from '@/stores/app'

const toolsUtil = {
    /**
     * 提取微信编码
     *
     * @return {Promise<any>}
     * @author zero
     */
    obtainWxCode(): Promise<any> {
        return new Promise((resolve, reject): void => {
            uni.login({
                success(response: UniApp.LoginRes): void {
                    if ('login:ok' === response.errMsg) {
                        resolve(response.code)
                    } else {
                        reject(response.errMsg)
                    }
                },
                fail(error): void {
                    reject(error)
                }
            })
        })
    },

    /**
     * 提取微信位置
     *
     * @return {Promise<any>}
     * @author zero
     */
    obtainWxLocation(): Promise<any> {
        return new Promise((resolve, reject): void => {
            uni.getLocation({
                type: 'wgs84',
                success: function (response: UniApp.GetLocationSuccess): void {
                    resolve(response)
                },
                fail(error): void {
                    reject(error)
                }
            })
        })
    },

    /**
     * 当前所在页面
     */
    currentPage() {
        const pages = getCurrentPages()
        const currentPage = pages[pages.length - 1]
        return currentPage || {}
    },

    /**
     * 树转数组
     *
     * @param {any[]} data
     * @param {any} props
     * @returns {any[]}
     * @author zero
     */
    treeToArray(data: any[], props: any = { children: 'children' }): any[] {
        data = cloneDeep(data)
        const { children } = props
        const newData: any[] = []
        const queue: any[] = []
        data.forEach((child: any) => queue.push(child))
        while (queue.length) {
            const item: any = queue.shift()
            if (item[children]) {
                item[children].forEach((child: any) => queue.push(child))
                delete item[children]
            }
            newData.push(item)
        }
        return newData
    },

    /**
     * 数组转树
     *
     * @param {any[]} data
     * @param {any} props
     * @returns {any[]}
     * @author zero
     */
    arrayToTree(data: any[], props: any = { id: 'id', parentId: 'pid', children: 'children' }): any[] {
        data = cloneDeep(data)
        const { id, parentId, children } = props
        const result: any[] = []
        const map: Map<any, any> = new Map()
        data.forEach((item): void => {
            map.set(item[id], item)
            const parent = map.get(item[parentId])
            if (parent) {
                parent[children] = parent[children] || []
                parent[children].push(item)
            } else {
                result.push(item)
            }
        })
        return result
    }
}

export default toolsUtil
