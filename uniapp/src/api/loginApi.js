// 退出系统
export function logoutApi() {
    return uni.$u.http.post('login/logout')
}

// 登录系统
export function loginApi(params) {
    return uni.$u.http.post('login/login', params, {isResults: true})
}

// 公众URL
export function oaCodeUrlApi() {
    const params = { url: location.href }
    return uni.$u.http.get('login/oaCodeUrl', params)
}

// 注册账号
export function registerApi(params) {
    return uni.$u.http.post('login/register', params)
}
