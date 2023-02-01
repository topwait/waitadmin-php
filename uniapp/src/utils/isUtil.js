export default {
    is(val, type) {
        return toString.call(val) === `[object ${type}]`
    },
    isDate(val) {
        return this.is(val, 'Date');
    },
    isNumber(val) {
        return this.is(val, 'Number');
    },
    isString(val) {
        return this.is(val, 'String');
    },
    isBoolean(val) {
        return this.is(val, 'Boolean');
    },
    isRegExp(val) {
        return this.is(val, 'RegExp');
    },
    isArray(val) {
        return val && Array.isArray(val);
    },
    isFunction(val) {
        return typeof val === 'function';
    },
    isObject(val) {
        return val !== null && this.is(val, 'Object')
    },
    isPromise(val) {
        return this.is(val, 'Promise') && 
            this.isObject(val) && 
            this.isFunction(val.then) && 
            this.isFunction(val.catch)
    },
    isNull(val) {
        return val === null
    },
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
    
        return false;
    },
    isUndefined(val) {
        return typeof val !== 'undefined';
    },
    isNotUndefined(val) {
        return !this.isUndefined(val);
    },
    isNullAndUndefined(val) {
        return this.isUndefined()(val) && this.isNull(val)
    },
    isNullOrUndefined(val) {
        return this.isUndefined(val) || this.isNull(val)
    },
    isWindow(val) {
        return typeof window !== 'undefined' && this.is(val, 'Window')
    },
    isElement(val) {
        return this.isObject(val) && !!val.tagName
    },
    isMap(val) {
        return this.is(val, 'Map')
    },
    isMobile() {
        
    },
    isEmail() {

    }
}
