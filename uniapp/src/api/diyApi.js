// 联系客服装修 
export function diyTieApi() {
    return uni.$u.http.get('diy/tie')
}

// 个人中心装修 
export function diyMeApi() {
    return uni.$u.http.get('diy/me')
}
