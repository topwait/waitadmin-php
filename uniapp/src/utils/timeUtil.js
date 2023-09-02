export default {
    /**
     * 格式化日期格式
     *
     * @param {Date} date 需格式化的日志
     * @return 格式化后的日期
     */
    formatDate(date) {
        let myyear = date.getFullYear()
        let mymonth = date.getMonth() + 1
        let myweekday = date.getDate()

        if (mymonth < 10) {
            mymonth = '0' + mymonth
        }

        if (myweekday < 10) {
            myweekday = '0' + myweekday
        }

        return myyear + '-' + mymonth + '-' + myweekday
    },
    /**
     * 时间戳转时间格式
     *
     * @param {Number} timestamp 时间戳
     * @param {String} format 时间格式
     * @returns 转换后的日期时间
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

        const newTime = format
            .replace(/YY/g, year)
            .replace(/MM/g, preArr[month] || month)
            .replace(/DD/g, preArr[day] || day)
            .replace(/hh/g, preArr[hour] || hour)
            .replace(/mm/g, preArr[min] || min)
            .replace(/ss/g, preArr[sec] || sec)

        return newTime
    },
    /**
     * 日期格式转时间戳
     *
     * @param {格式化后的日期} datetime 日期时间
     * @returns 转换后的时间戳(秒)
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
     * 获取的当前时间戳
     */
    time() {
        let date = new Date()
        return date.getTime()
    },
    /**
     * 返回今日开始和结束的时间戳
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
     */
    year() {
        let startDate = new Date(new Date().getFullYear(), 0, 1)
        let endDate = new Date(new Date(new Date().getFullYear() + 1, 0, 0).setHours(23, 59, 59, 59))
        let startTime = this.toTimeFormat(this.formatDate(startDate))
        let endTime = this.toTimeFormat(this.formatDate(endDate))
        return [startTime, endTime + (86400 - 1)]
    }
}
