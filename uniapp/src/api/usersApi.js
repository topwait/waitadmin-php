// 登录系统
export function loginApi(params) {
    return uni.$u.http.post('login/login', params)
}

// 注册账号
export function registerApi(params) {
    return uni.$u.http.post('login/register', params)
}

// 用户信息
export function getUserCenterApi() {
    return uni.$u.http.post('users/center')
}