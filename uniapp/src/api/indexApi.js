// const http = uni.$u.http

// 系统配置
export function getSysConfigApi() {
    return uni.$u.http.get('index/config')
}

// 发送短信
export function sendSmsApi(params) {
    return uni.$u.http.get('index/sendSms', params)
}