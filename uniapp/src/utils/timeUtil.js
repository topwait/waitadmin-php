export default {
    /**
     * 格式化日期格式
     * @param {date} date 日期时间
     * @return {string} 格式日期
     */
    formatDate(date) {
        let myYear = date.getFullYear()
        let myMonth = date.getMonth() + 1
        let myWeekday = date.getDate()

        if (myMonth < 10) {
            myMonth = '0' + myMonth
        }

        if (myWeekday < 10) {
            myWeekday = '0' + myWeekday
        }

        return myYear + '-' + myMonth + '-' + myWeekday
    },

    /**
     * 时间戳转时间格式
     * @param {number} timestamp 时间戳值
     * @param {string} format 时间格式
     * @returns {string} 日期时间
     */
    toDateFormat(timestamp, format = 'YY-MM-DD hh:mm:ss') {
        if (String(timestamp).length <= 10) {
            timestamp = timestamp * 1000
        }

        const date = new Date(timestamp)

        const year = date.getFullYear(),
            month = date.getMonth() + 1,
            day = date.getDate(),
            hour = date.getHours(),
            min = date.getMinutes(),
            sec = date.getSeconds()

        const preArr = Array.apply(null, Array(10)).map(function (elem, index) {
            return '0' + index
        })

        return format
            .replace(/YY/g, year + '')
            .replace(/MM/g, preArr[month] || month)
            .replace(/DD/g, preArr[day] || day)
            .replace(/hh/g, preArr[hour] || hour)
            .replace(/mm/g, preArr[min] || min)
            .replace(/ss/g, preArr[sec] || sec)
    },

    /**
     * 日期格式转时间戳(秒)
     * @param {string} datetime 日期时间
     * @returns {number} 秒级时间戳
     */
    toTimeFormat(datetime) {
        let f = datetime.split(' ', 2)
        let d = (f[0] ? f[0] : '').split('-', 3)
        let t = (f[1] ? f[1] : '').split(':', 3)
        return new Date(
            parseInt(d[0], 10) || null,
            (parseInt(d[1], 10) || 1) - 1,
            parseInt(d[2], 10) || null,
            parseInt(t[0], 10) || null,
            parseInt(t[1], 10) || null,
            parseInt(t[2], 10) || null
        ).getTime() / 1000
    },

    /**
     * 获取当前毫秒数
     * @returns {number} 毫秒时间戳
     */
    timestamp() {
        const date = new Date()
        return date.getTime()
    },

    /**
     * 获取当前的秒数
     * @returns {number} 秒时间戳
     */
    time() {
        const date = new Date()
        return parseInt(date.getTime() / 1000 + '')
    },

    /**
     * 返回今日开始和结束的时间戳
     * @returns {number[]}
     */
    today() {
        let date = new Date()
        date.setHours(0)
        date.setMinutes(0)
        date.setSeconds(0)
        date.setMilliseconds(0)
        let t = date.getTime() / 1000
        return [t, t + (86400 - 1)]
    },

    /**
     * 返回昨日开始和结束的时间戳
     * @returns {number[]}
     */
    yesterday() {
        let date = new Date()
        date.setHours(0)
        date.setMinutes(0)
        date.setSeconds(0)
        date.setMilliseconds(0)
        let t = this.today()[0] - 86400
        return [t, t + (86400 - 1)]
    },

    /**
     * 返回本周开始和结束的时间戳
     * @returns {number[]}
     */
    week() {
        let startDate = new Date(new Date().setHours(0, 0, 0) - (new Date().getDay() - 1) * 24 * 60 * 60 * 1000)
        let endDate = new Date(new Date().setHours(0, 0, 0) + (7 - new Date().getDay()) * 24 * 60 * 60 * 1000)
        let startTime = this.toTimeFormat(this.formatDate(startDate))
        let endTime = this.toTimeFormat(this.formatDate(endDate))
        return [startTime, endTime + (86400 - 1)]
    },

    /**
     * 返回本月开始和结束的时间戳
     * @returns {number[]}
     */
    month() {
        let startDate = new Date(new Date(new Date().getFullYear(), new Date().getMonth(), 1).setHours(0, 0, 0))
        let endDate = new Date(new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).setHours(23, 59, 59, 59))
        let startTime = this.toTimeFormat(this.formatDate(startDate))
        let endTime = this.toTimeFormat(this.formatDate(endDate))
        return [startTime, endTime + (86400 - 1)]
    },

    /**
     * 返回今年开始和结束的时间戳
     * @returns {number[]}
     */
    year() {
        let startDate = new Date(new Date().getFullYear(), 0, 1)
        let endDate = new Date(new Date(new Date().getFullYear() + 1, 0, 0).setHours(23, 59, 59, 59))
        let startTime = this.toTimeFormat(this.formatDate(startDate))
        let endTime = this.toTimeFormat(this.formatDate(endDate))
        return [startTime, endTime + (86400 - 1)]
    }
}
