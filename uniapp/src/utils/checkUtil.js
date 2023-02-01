export default {
    // 类型验证
    is(val, type) {
        return toString.call(val) === `[object ${type}]`
    },
    // 开发模式
    isDevMode() {
        return import.meta.env.DEV
    },
    // 生成模型
    isProdMode() {
        return import.meta.env.PROD
    },
    // Map类型
    isMap(val) {
        return this.is(val, 'Map')
    },
    // Date类型
    isDate(val) {
        return this.is(val, 'Date')
    },
    // Number类型
    isNumber(val) {
        return this.is(val, 'Number')
    },
    // String类型
    isString(val) {
        return this.is(val, 'String')
    },
    // Boolean类型
    isBoolean(val) {
        return this.is(val, 'Boolean')
    },
    // RegExp类型
    isRegExp(val) {
        return this.is(val, 'RegExp')
    },
    // Array类型
    isArray(val) {
        return val && Array.isArray(val)
    },
    // Function类型
    isFunction(val) {
        return typeof val === 'function'
    },
    // Object类型
    isObject(val) {
        return val !== null && this.is(val, 'Object')
    },
    // Promise类型
    isPromise(val) {
        return this.is(val, 'Promise') &&
            this.isObject(val) &&
            this.isFunction(val.then) &&
            this.isFunction(val.catch)
    },
    // Null类型
    isNull(val) {
        return val === null
    },
    // Empty类型
    isEmpty(val) {
        if (this.isArray(val) || this.isString(val)) {
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
    // Undefined类型
    isUndefined(val) {
        return typeof val !== 'undefined'
    },
    // Undefined且Null
    isNullAndUndefined(val) {
        return this.isUndefined()(val) && this.isNull(val)
    },
    // Undefined或Null
    isNullOrUndefined(val) {
        return this.isUndefined(val) || this.isNull(val)
    },
    // Window类型
    isWindow(val) {
        return typeof window !== 'undefined' && this.is(val, 'Window')
    },
    // Element类型
    isElement(val) {
        return this.isObject(val) && !!val.tagName
    },
    // 是否是手机号
    isMobile(val) {
        return /^0\d{2,3}-?\d{7,8}$/.test(val)
    },
    // 是否是邮箱号
    isEmail(val) {
        return /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((.[a-zA-Z0-9_-]{2,3}){1,2})$/.test(val)
    }
}
