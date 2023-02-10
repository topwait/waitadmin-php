// 退出系统
export function logoutApi() {
    return uni.$u.http.post('login/logout')
}

// 登录系统
export function loginApi(params) {
    return uni.$u.http.post('login/login', params)
}

// 注册账号
export function registerApi(params) {
    return uni.$u.http.post('login/register', params)
}

// 忘记密码
export function forgetPwdApi(params) {
    return uni.$u.http.post('login/forgetPwd', params)
}

// 用户信息
export function getUserInfoApi() {
    return uni.$u.http.post('users/info')
}