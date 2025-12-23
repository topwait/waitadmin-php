const datetimeUtil = {
    /**
     * 格式化日期格式
     *
     * @param {Date} dateTime 日期对象
     * @param {Date} format 时间格式
     * @return {string} 格式化后的日期
     * @example:
     *    原始: new Date() => Fri May 31 2024 15:43:03 GMT+0800 (中国标准时间)
     *    最终: 2024-05-31
     */
    formatDate(dateTime: Date, format: string = 'YYYY-MM-DD'): string {
        const timestamp: number = Number(dateTime)
        const date: Date = new Date(timestamp)
        const opt: any = {
            'YYYY': String(date.getFullYear()),         // 年
            'YY': String(date.getFullYear()).slice(-2), // 年
            'M+': String(date.getMonth() + 1),          // 月
            'D+': date.getDate().toString(),            // 日
            'h+': date.getHours().toString(),           // 时
            'm+': date.getMinutes().toString(),         // 分
            's+': date.getSeconds().toString()          // 秒
        }

        let ret
        for (const k in opt) {
            ret = new RegExp(`(${k})`).exec(format)
            if (ret) {
                const replaceValue: string = ret[1]?.length === 1 ? opt[k] : opt[k].padStart(ret[1]?.length, '0')
                format = format.replace(ret[1] || '', String(replaceValue))
            }
        }

        return format
    },

    /**
     * 时间戳转时间格式
     *
     * @param {number} timestamp 时间戳
     * @param {string} format 时间格式
     * @returns {string} 转换后的日期时间
     * @example: 1717139415 => 2024-05-31 15:10:15
     */
    timestampToDate(timestamp: number, format: string = 'YYYY-MM-DD hh:mm:ss'): string {
        if (!timestamp) {
            timestamp = Number(new Date())
        }

        if (timestamp.toString().length === 10) {
            timestamp *= 1000
        }

        const date: Date = new Date(timestamp)
        const opt: any = {
            'YYYY': String(date.getFullYear()),         // 年
            'YY': String(date.getFullYear()).slice(-2), // 年
            'M+': (date.getMonth() + 1).toString(),     // 月
            'D+': date.getDate().toString(),            // 日
            'h+': date.getHours().toString(),           // 时
            'm+': date.getMinutes().toString(),         // 分
            's+': date.getSeconds().toString()          // 秒
        }

        let ret
        for (const k in opt) {
            ret = new RegExp(`(${k})`).exec(format)
            if (ret) {
                const replaceValue: string = ret[1]?.length === 1 ? opt[k] : opt[k].padStart(ret[1]?.length, '0')
                format = format.replace(ret[1] || '', replaceValue)
            }
        }

        return format
    },

    /**
     * 日期格式转时间戳
     *
     * @param {string} datetime 日期时间
     * @returns {number} 转换后的时间戳(秒)
     * @example: 2024-05-31 15:10:15 => 1717139415
     */
    dateToTimestamp(datetime: string): number {
        const f: string[] = datetime.split(' ', 2)
        const d: string[] = (f[0] ? f[0] : '').split('-', 3)
        const t: string[] = (f[1] ? f[1] : '').split(':', 3)
        return new Date(
            parseInt(d[0] || '0', 10) || 0,
            (parseInt(d[1] || '0', 10) || 1) - 1,
            parseInt(d[2] || '0', 10) || 0,
            parseInt(t[0] || '0', 10) || 0,
            parseInt(t[1] || '0', 10) || 0,
            parseInt(t[2] || '0', 10) || 0
        ).getTime() / 1000
    },

    /**
     * 秒数转分钟格式
     *
     * @param {number} time - 秒数(如: 120)
     * @returns {string} 分钟格式时间: 02:00
     */
    secondToMin(time: number): string {
        let minute: string | number = parseInt(String(time / 60))
        let sec: string = `${Math.round(time % 60)}`
        const isM0 = ':'
        if (minute === 0) {
            minute = '00'
        } else if (minute < 10) {
            minute = `0${minute}`
        }
        if (sec.length === 1) {
            sec = `0${sec}`
        }
        return minute + isM0 + sec
    },

    /**
     * 获取当前日期时间
     *
     * @returns {string}
     * @example: 2024-05-31 15:54:00
     */
    datetime(): string {
        return this.timestampToDate(this.time())
    },

    /**
     * 获取的当前时间戳
     *
     * @returns {number}
     * @example: 1717142058720
     */
    time(): number {
        const date: Date = new Date()
        return date.getTime()
    },

    /**
     * 返回今日开始和结束的时间戳
     *
     * @returns {number[]}
     * @example: [1717084800, 1717171199]
     */
    today(): number[] {
        const date: Date = new Date()
        date.setHours(0)
        date.setMinutes(0)
        date.setSeconds(0)
        date.setMilliseconds(0)
        const t: number = date.getTime() / 1000
        return [t, t + (86400 - 1)]
    },

    /**
     * 返回昨日开始和结束的时间戳
     *
     * @returns {number[]}
     * @example: [1716998400, 1717084799]
     */
    yesterday(): number[] {
        const date: Date = new Date()
        date.setHours(0)
        date.setMinutes(0)
        date.setSeconds(0)
        date.setMilliseconds(0)
        const t: number = (this.today()[0] || 0) - 86400
        return [t, t + (86400 - 1)]
    },

    /**
     * 返回本周开始和结束的时间戳
     *
     * @returns {number[]}
     * @example: [1716739200, 1717343999]
     */
    week(): number[] {
        const startDate: Date = new Date(
            new Date().setHours(0, 0, 0) - (new Date().getDay() - 1) * 24 * 60 * 60 * 1000
        )
        const endDate: Date = new Date(
            new Date().setHours(0, 0, 0) + (7 - new Date().getDay()) * 24 * 60 * 60 * 1000
        )
        const startTime: number = this.dateToTimestamp(this.formatDate(startDate))
        const endTime: number = this.dateToTimestamp(this.formatDate(endDate))
        return [startTime, endTime + (86400 - 1)]
    },

    /**
     * 返回本月开始和结束的时间戳
     *
     * @returns {number[]}
     * @example: [1714492800, 1717171199]
     */
    month(): number[] {
        const startDate: Date = new Date(new Date(
            new Date().getFullYear(), new Date().getMonth(), 1).setHours(0, 0, 0)
        )
        const endDate: Date = new Date(
            new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0)
                .setHours(23, 59, 59, 59)
        )
        const startTime: number = this.dateToTimestamp(this.formatDate(startDate))
        const endTime: number = this.dateToTimestamp(this.formatDate(endDate))
        return [startTime, endTime + (86400 - 1)]
    },

    /**
     * 返回今年开始和结束的时间戳
     *
     * @returns {number[]}
     * @example: [1704038400, 1735660799]
     */
    year(): number[] {
        const startDate: Date = new Date(new Date().getFullYear(), 0, 1)
        const endDate: Date = new Date(
            new Date(new Date().getFullYear() + 1, 0, 0)
                .setHours(23, 59, 59, 59)
        )
        const startTime: number = this.dateToTimestamp(this.formatDate(startDate))
        const endTime: number = this.dateToTimestamp(this.formatDate(endDate))
        return [startTime, endTime + (86400 - 1)]
    }
}

export default datetimeUtil
