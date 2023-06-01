
export default class {

    /**
     * 编辑信息
     */
    static edit(params) {
        return uni.$u.http.post('user/edit', params)
    }

    /**
     * 用户信息
     */
    static info() {
        return uni.$u.http.get('user/info')
    }

    /**
     * 用户中心
     */
    static center() {
        return uni.$u.http.get('user/center')
    }

    /**
     * 忘记密码
     */
    static forgetPwd(params) {
        return uni.$u.http.post('user/forgetPwd', params)
    }

    /**
     * 修改密码
     */
    static changePwd(params) {
        return uni.$u.http.post('user/changePwd', params)
    }

    /**
     * 绑定微信
     */
    static bindWeChat(params) {
        return uni.$u.http.post('user/bindWeChat', params)
    }

    /**
     * 绑定手机
     */
    static bindMobile(params) {
        return uni.$u.http.post('user/bindMobile', params)
    }

    /**
     * 绑定邮箱
     */
    static bindEmail(params) {
        return uni.$u.http.post('user/bindEmail', params)
    }
}
