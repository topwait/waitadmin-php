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

// 修改密码
export function changePwdApi(params) {
    return uni.$u.http.post('login/changePwd', params)
}

// 忘记密码
export function forgetPwdApi(params) {
    return uni.$u.http.post('login/forgetPwd', params)
}

// 公众号url
export function oaCodeUrlApi() {
    const params = { url: location.href }
    return uni.$u.http.get('login/oaCodeUrl', params)
}

// 用户信息
export function userInfoApi() {
    return uni.$u.http.post('users/info')
}

// 用户编辑
export function userEditApi(params) {
    return uni.$u.http.post('users/edit', params)
}
