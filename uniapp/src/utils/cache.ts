const cacheUtil = {
    namespace: 'wi_',

    /**
     * 设置缓存
     *
     * @param {string} key 键
     * @param {any} value 值
     * @param {number} [expire] 过期时长(秒)
     * @author zero
     */
    set(key: string, value: any, expire: number = 0): boolean {
        key = this.namespace + key
        let data = value
        if (expire) {
            const time: number = Math.round(new Date().getTime() / 1000)
            data = {
                expire: time + expire,
                value: value
            }
        }

        if (typeof data === 'object') {
            data = JSON.stringify(data)
        }

        try {
            uni.setStorageSync(key, data)
            return true
        } catch {
            return false
        }
    },

    /**
     * 读取缓存
     *
     * @param key {string} 键
     * @param isShowExpire {boolean} 是否显示过期时间
     * @returns {any}
     * @author zero
     */
    get(key: string, isShowExpire?: boolean): any {

        key = this.namespace + key
        const data: string | null = uni.getStorageSync(key)

        if (data === undefined || data === null) {
            if (!isShowExpire) {
                return undefined
            }
            return { expire: undefined, value: undefined }
        }

        try {
            const time: number = Math.round(new Date().getTime() / 1000)
            const { value, expire } = JSON.parse(data)
            if (expire && expire < time) {
                uni.removeStorageSync(key)
                return undefined
            }

            if (value) {
                if (!isShowExpire) {
                    return value
                }
                return { expire, value }
            }

            const val = JSON.parse(data)
            if (val !== undefined) {
                return val
            }

            return data
        } catch {
            return data
        }
    },

    /**
     * 删除缓存
     *
     * @param {string} key 键
     * @author zero
     */
    remove(key: string): void {
        key = this.namespace + key
        uni.removeStorageSync(key)
    },

    /**
     * 清空缓存
     *
     * @author zero
     */
    clear(): void {
        uni.clearStorageSync()
    }
}

export default cacheUtil
