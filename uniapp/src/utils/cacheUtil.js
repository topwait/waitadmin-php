const postfix = '_expiry'
export default {
    set(k, v, t) {
        uni.setStorageSync(k, v)
        const seconds = parseInt(t)
        if (seconds > 0) {
            let timestamp = Date.parse(new Date())
            timestamp = timestamp / 1000 + seconds
            uni.setStorageSync(k + postfix, timestamp + '')
        } else {
            uni.removeStorageSync(k + postfix)
        }
    },
    get(k, def) {
        const deadtime = parseInt(uni.getStorageSync(k + postfix))
        if (deadtime) {
            if (parseInt(deadtime) < Date.parse(new Date()) / 1000) {
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
    remove(k) {
        uni.removeStorageSync(k)
        uni.removeStorageSync(k + postfix)
    },
    clear() {
        uni.clearStorageSync()
    }
}
