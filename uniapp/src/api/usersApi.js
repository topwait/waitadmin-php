// 用户信息
export function userInfoApi() {
    return uni.$u.http.post('users/info')
}

// 用户编辑
export function userEditApi(params) {
    return uni.$u.http.post('users/edit', params)
}
