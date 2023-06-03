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
    static login(params) {
        return uni.$u.http.post('login/login', params, {isResults: true})
    }

    /**
     * 注册账号
     */
    static register(params) {
        return uni.$u.http.post('login/register', params)
    }

    /**
     * 公众URL
     */
    static oaCodeUrl() {
        const params = { url: location.href }
        return uni.$u.http.get('login/oaCodeUrl', params)
    }
}
