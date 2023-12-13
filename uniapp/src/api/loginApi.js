export default class {
    /**
     * 公众URL
     */
    static oaCodeUrl() {
        const param = {
            url: location.href
        }
        return uni.$u.http.get('login/oaCodeUrl', param)
    }

    /**
     * 退出系统
     */
    static logout() {
        return uni.$u.http.post('login/logout')
    }

    /**
     * 登录系统
     */
    static login(params) {
        let param = {}
        switch (params.scene) {
            case 'account':
                param = {
                    scene: params.scene,
                    account: params.account,
                    password: params.password
                }
                break
            case 'mobile':
                param = {
                    scene: params.scene,
                    mobile: params.mobile,
                    code: params.code
                }
                break
            case 'wx':
                param = {
                    scene: params.scene,
                    code: params.code,
                    wxCode: params.wxCode
                }
                break
            case 'oa':
                param = {
                    scene: params.scene,
                    code: params.code,
                    state: params.state
                }
                break
            case 'ba':
                param = {
                    scene: params.scene,
                    mobile: params.mobile,
                    code: params.code,
                    sign: params.sign
                }
                break
        }

        const headers = { Structure: true }
        return uni.$u.http.post('login/login', param, headers)
    }

    /**
     * 注册账号
     */
    static register(params) {
        const param = {
            code: params.code,
            mobile: params.mobile,
            account: params.account,
            password: params.password
        }
        return uni.$u.http.post('login/register', param)
    }
}
