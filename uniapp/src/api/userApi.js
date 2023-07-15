export default class {
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
     * 编辑信息
     */
    static edit(params) {
        const param = {
            scene: params.scene || '',
            value: params.value || ''
        }
        return uni.$u.http.post('user/edit', param)
    }

    /**
     * 忘记密码
     */
    static forgetPwd(params) {
        const param = {
            code: params.code,
            mobile: params.mobile,
            password: params.newPassword
        }
        return uni.$u.http.post('user/forgetPwd', param)
    }

    /**
     * 修改密码
     */
    static changePwd(params) {
        const param = {
            newPassword: params.newPassword,
            oldPassword: params.oldPassword
        }
        return uni.$u.http.post('user/changePwd', param)
    }

    /**
     * 绑定微信
     */
    static bindWeChat(params) {
        const param = {
            code: params.code
        }
        return uni.$u.http.post('user/bindWeChat', param)
    }

    /**
     * 绑定手机
     */
    static bindMobile(params) {
        const param = {
            mobile: params.mobile,
            code: params.code,
            type: params.type
        }
        return uni.$u.http.post('user/bindMobile', param)
    }

    /**
     * 绑定邮箱
     */
    static bindEmail(params) {
        const param = {
            email: params.email,
            code: params.code
        }
        return uni.$u.http.post('user/bindEmail', param)
    }
}
