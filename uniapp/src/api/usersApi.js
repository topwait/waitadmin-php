const http = uni.$u.http

export function getUserInfo(params) {
    return http.get('index/config', params)
}