export default {
    /**
     * 类型验证
     * @param val
     * @param type
     * @returns {boolean}
     */
    is(val, type) {
        return toString.call(val) === `[object ${type}]`
    },

    /**
     * 开发模式
     * @returns {boolean}
     */
    isDevMode() {
        return import.meta.env.DEV
    },

    /**
     * 生产模型
     * @returns {boolean}
     */
    isProdMode() {
        return import.meta.env.PROD
    },

    /**
     * Map类型
     * @param val
     * @returns {boolean}
     */
    isMap(val) {
        return this.is(val, 'Map')
    },

    /**
     * Date类型
     * @param val
     * @returns {boolean}
     */
    isDate(val) {
        return this.is(val, 'Date')
    },

    /**
     * Number类型
     * @param val
     * @returns {boolean}
     */
    isNumber(val) {
        return this.is(val, 'Number')
    },

    /**
     * String类型
     * @param val
     * @returns {boolean}
     */
    isString(val) {
        return this.is(val, 'String')
    },

    /**
     * Boolean类型
     * @param val
     * @returns {boolean}
     */
    isBoolean(val) {
        return this.is(val, 'Boolean')
    },

    /**
     * RegExp类型
     * @param val
     * @returns {boolean}
     */
    isRegExp(val) {
        return this.is(val, 'RegExp')
    },

    /**
     * Array类型
     * @param val
     * @returns {arg is any[]}
     */
    isArray(val) {
        return val && Array.isArray(val)
    },

    /**
     * Function类型
     * @param val
     * @returns {boolean}
     */
    isFunction(val) {
        return typeof val === 'function'
    },

    /**
     * Object类型
     * @param val
     * @returns {boolean}
     */
    isObject(val) {
        return val !== null && this.is(val, 'Object')
    },

    /**
     * Promise类型
     * @param val
     * @returns {boolean}
     */
    isPromise(val) {
        return this.is(val, 'Promise') &&
            this.isObject(val) &&
            this.isFunction(val.then) &&
            this.isFunction(val.catch)
    },

    /**
     * Null类型
     * @param val
     * @returns {boolean}
     */
    isNull(val) {
        return val === null
    },

    /**
     * Empty类型
     * @param val
     * @returns {boolean}
     */
    isEmpty(val) {
        if (this.isString(val) || this.isArray(val)) {
            return val.length === 0
        }

        if (val instanceof Map || val instanceof Set) {
            return val.size === 0
        }

        if (this.isObject(val)) {
            return Object.keys(val).length === 0
        }

        return false
    },

    /**
     * Undefined类型
     * @param val
     * @returns {boolean}
     */
    isUndefined(val) {
        return typeof val !== 'undefined'
    },

    /**
     * Window类型
     * @param val
     * @returns {boolean}
     */
    isWindow(val) {
        return typeof window !== 'undefined' && this.is(val, 'Window')
    },

    /**
     * 是否是手机号
     * @param val
     * @returns {boolean}
     */
    isMobile(val) {
        return /^1[3-9]\d{9}$/.test(val)
    },

    /**
     * 是否是邮箱号
     * @param val
     * @returns {boolean}
     */
    isEmail(val) {
        return /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((.[a-zA-Z0-9_-]{2,3}){1,2})$/.test(val)
    }
}
