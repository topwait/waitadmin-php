import type { RequestTask } from './type'

const cancelerMap: Map<string, RequestTask> = new Map<string, RequestTask>()

/**
 * 用于管理并控制 HTTP 请求的取消逻辑
 */
export class RequestCancel {
    private static instance?: RequestCancel

    /**
     * 创建或获取 RequestCancel 的单例实例
     *
     * @returns RequestCancel 的单例实例
     * @author zero
     */
    static createInstance(): RequestCancel {
        return this.instance || (this.instance = new RequestCancel())
    }

    /**
     * 添加一个请求任务到取消管理中
     *
     * @param url 请求的URL, 作为唯一标识
     * @param requestTask 请求任务对象, 必须包含 abort() 方法用于取消请求
     * @author zero
     */
    add(url: string, requestTask: RequestTask):void {
        // 先尝试移除可能已存在的相同URL的请求 (避免重复)
        this.remove(url)

        // 双重检查确保Map中不存在该URL
        if (cancelerMap.has(url)) {
            cancelerMap.delete(url)
        }

        // 将新请求任务添加到Map中
        cancelerMap.set(url, requestTask)
    }

    /**
     * 移除并取消指定URL的请求任务
     *
     * @param url 需要取消的请求的 URL
     * @author zero
     */
    remove(url: string): void {
        if (cancelerMap.has(url)) {
            const requestTask = cancelerMap.get(url)
            requestTask && requestTask.abort()
            cancelerMap.delete(url)
        }
    }
}

const requestCancel: RequestCancel = RequestCancel.createInstance()
export default requestCancel
