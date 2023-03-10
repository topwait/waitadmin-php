
// 编辑信息
export function userEditApi(params) {
    return uni.$u.http.post('user/edit', params)
}

// 装修布局
export function userDesignApi() {
    return uni.$u.http.get('user/design')
}

// 用户信息
export function userInfoApi() {
    return uni.$u.http.post('user/info')
}

// 用户中心
export function userCenterApi() {
    return uni.$u.http.get('user/center')
}
