// 获取文章分类
export function getCategoryApi() {
    return uni.$u.http.get('article/category')
}

// 获取文章列表
export function getArticleApi(params) {
    return uni.$u.http.get('article/lists', params)
}


// 获取文章详情
export function getDetailApi(params) {
    return uni.$u.http.get('article/detail', params)
}
