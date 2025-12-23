import { isObject } from 'lodash-es'
export {
    isArray,
    isBoolean,
    isDate,
    isObject,
    isFunction,
    isString,
    isNumber,
    isNull
} from 'lodash-es'

const validateUtil = {
    /**
     * 类型验证
     *
     * @param {any} val  值
     * @param {any} type 类型
     */
    is(val: any, type: any): boolean {
        return toString.call(val) === `[object ${type}]`
    },

    /**
     * Map类型
     *
     * @param {any} val 值
     * @returns {boolean} true=是, false=否
     */
    isMap(val: any): boolean {
        return this.is(val, 'Map')
    },

    /**
     * Date类型
     *
     * @param {any} val 值
     * @returns {boolean} true=是, false=否
     */
    isDate(val: any): boolean {
        return this.is(val, 'Date')
    },

    /**
     * Number类型
     *
     * @param {any} val 值
     * @returns {boolean} true=是, false=否
     */
    isNumber(val: any): boolean {
        return this.is(val, 'Number')
    },

    /**
     * String类型
     *
     * @param {any} val 值
     * @returns {boolean} true=是, false=否
     */
    isString(val: any): boolean {
        return this.is(val, 'String')
    },

    /**
     * Boolean类型
     *
     * @param {any} val 值
     * @returns {boolean} true=是, false=否
     */
    isBoolean(val: any): boolean {
        return this.is(val, 'Boolean')
    },

    /**
     * RegExp类型
     *
     * @param {any} val 值
     * @returns {boolean} true=是, false=否
     */
    isRegExp(val: any): boolean {
        return this.is(val, 'RegExp')
    },

    /**
     * Array类型
     *
     * @param {any} val 值
     * @returns {boolean} true=是, false=否
     */
    isArray(val: any): boolean {
        return val && Array.isArray(val)
    },

    /**
     * Function类型
     *
     * @param {any} val 值
     * @returns {boolean} true=是, false=否
     */
    isFunction(val: any): boolean {
        return typeof val === 'function'
    },

    /**
     * Object类型
     *
     * @param {any} val 值
     * @returns {boolean} true=是, false=否
     */
    isObject(val: any): boolean {
        return val !== null && this.is(val, 'Object')
    },

    /**
     * 是否是手机号
     *
     * @param {string} val 值
     * @returns {boolean} true=是, false=否
     */
    isMobile(val: string): boolean {
        return /^1[3-9]\d{9}$/.test(val)
    },

    /**
     * 是否是邮箱号
     *
     * @param val 值
     * @returns {boolean} true=是, false=否
     */
    isEmail(val: string): boolean {
        return /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((.[a-zA-Z0-9_-]{2,3}){1,2})$/.test(val)
    },

    /**
     * 是否是[http/邮件/电话号码]
     *
     * @param {string} path 值
     * @returns {boolean} true=是, false=否
     */
    isExternal(path: string): boolean {
        return /^(https?:|mailto:|tel:)/.test(path)
    },

    /**
     * 是否为空
     *
     * @param {any} value 值
     * @returns {boolean} true=是, false=否
     */
    isEmpty(value: any): boolean {
        return !(value !== null && value !== '' && typeof value !== 'undefined')
    },

    /**
     * 是否为空对象
     *
     * @param {object|null|undefined} target
     * @returns {boolean} true=是, false=否
     */
    isEmptyObject(target: object | null | undefined): boolean {
        if (!target) {
            return false
        }
        return isObject(target) && !Object.keys(target).length
    }
}

export default validateUtil
