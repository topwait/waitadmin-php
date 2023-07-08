export default class {
    /**
     * 退出系统
     */
    static logout() {
        return uni.$u.http.post('login/logout')
    }

    /**
     * 登录系统
     */
    static login(p) {
        let param = {}
        switch (p.scene) {
            case 'account':
                param = {
                    scene: p.scene,
                    account: p.account,
                    password: p.password
                }
                break
            case 'mobile':
                param = {
                    scene: p.scene,
                    mobile: p.mobile,
                    code: p.code
                }
                break
            case 'wx':
                param = {
                    scene: p.scene,
                    code: p.code,
                    wxCode: p.wxCode || ''
                }
                break
            case 'oa':
                param = {
                    scene: p.scene,
                    code: p.code,
                    state: p.state || ''
                }
                break
            case 'ba':
                param = {
                    scene: p.scene,
                    mobile: p.mobile,
                    code: p.code,
                    sign: p.sign
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

    /**
     * 公众URL
     */
    static oaCodeUrl() {
        const param = {
            url: location.href
        }
        return uni.$u.http.get('login/oaCodeUrl', param)
    }
}
