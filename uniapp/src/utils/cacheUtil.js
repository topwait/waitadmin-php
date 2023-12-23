const postfix = '_expiry'

export default {
    /**
     * 设置缓存
     * @param {string} k 键
     * @param {string} v 值
     * @param {number} t 过期时长(秒)
     */
    set(k, v, t) {
        uni.setStorageSync(k, v)
        const seconds = parseInt(t + '')
        if (seconds > 0) {
            let timestamp = Date.parse(new Date() + '')
            timestamp = timestamp / 1000 + seconds
            uni.setStorageSync(k + postfix, timestamp + '')
        } else {
            uni.removeStorageSync(k + postfix)
        }
    },

    /**
     * 读取缓存
     * @param k {string} 键
     * @param def {number|string|boolean} 默认值
     * @returns {boolean|any}
     */
    get(k, def) {
        const datetime = parseInt(uni.getStorageSync(k + postfix))
        if (datetime) {
            if (datetime < Date.parse(new Date() + '') / 1000) {
                if (def) {
                    return def
                }
                return false

            }
        }
        const res = uni.getStorageSync(k)
        if (res) {
            return res
        }
        if (def === undefined || def === '') {
            def = false
        }
        return def
    },

    /**
     * 删除缓存
     * @param {string} k 键
     */
    remove(k) {
        uni.removeStorageSync(k)
        uni.removeStorageSync(k + postfix)
    },

    /**
     * 清空缓存
     */
    clear() {
        uni.clearStorageSync()
    }
}
