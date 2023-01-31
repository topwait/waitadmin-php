// const http = uni.$u.http

// 系统配置
export function getSysConfigApi() {
    return uni.$u.http.get('index/config')
}