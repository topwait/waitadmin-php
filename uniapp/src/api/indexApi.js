export default class {
    /**
     * 首页数据
     */
    static index() {
        return uni.$u.http.get('index/index')
    }

    /**
     * 系统配置
     */
    static config() {
        return uni.$u.http.get('index/config')
    }

    /**
     * 政策协议
     */
    static policy({ type }) {
        return uni.$u.http.get('index/policy', { type })
    }

    /**
     * 发送短信
     */
    static sendSms(params) {
        return uni.$u.http.post('index/sendSms', params)
    }

    /**
     * 发送邮件
     */
    static sendEmail(params) {
        return uni.$u.http.post('index/sendEmail', params)
    }
}
