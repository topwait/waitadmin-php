
// 编辑信息
export function userEditApi(params) {
    return uni.$u.http.post('user/edit', params)
}

// 用户信息
export function userInfoApi() {
    return uni.$u.http.post('user/info')
}

// 用户中心
export function userCenterApi() {
    return uni.$u.http.get('user/center')
}

// 忘记密码
export function forgetPwdApi(params) {
    return uni.$u.http.post('user/forgetPwd', params)
}

// 修改密码
export function changePwdApi(params) {
    return uni.$u.http.post('user/changePwd', params)
}

// 绑定微信
export function bindWeChatApi(params) {
    return uni.$u.http.post('user/bindWeChat', params)
}

// 绑定手机
export function bindMobileApi(params) {
    return uni.$u.http.post('user/bindMobile', params)
}

// 绑定邮箱
export function bindEmailApi(params) {
    return uni.$u.http.post('user/bindEmail', params)
}
