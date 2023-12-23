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
    static policy(type) {
        return uni.$u.http.get('index/policy', { type })
    }

    /**
     * 发送短信
     */
    static sendSms(params) {
        const param = {
            scene: params.scene,
            mobile: params.mobile
        }
        return uni.$u.http.post('index/sendSms', param)
    }

    /**
     * 发送邮件
     */
    static sendEmail(params) {
        const param = {
            scene: params.scene,
            email: params.email
        }
        return uni.$u.http.post('index/sendEmail', param)
    }
}
