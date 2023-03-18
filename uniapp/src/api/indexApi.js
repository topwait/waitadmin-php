// 首页数据
export function getIndexApi() {
    return uni.$u.http.get('index/index')
}

// 系统配置
export function getSysConfigApi() {
    return uni.$u.http.get('index/config')
}

// 政策协议
export function getPolicyApi(params) {
    return uni.$u.http.get('index/policy', {type: params.type})
}

// 发送短信
export function sendSmsApi(params) {
    return uni.$u.http.post('index/sendSms', params)
}

// 发送邮件
export function sendEmailApi(params) {
    return uni.$u.http.post('index/sendEmail', params)
}
