// 获取文章分类
export function getArticleCateApi() {
    return uni.$u.http.get('article/category')
}

// 获取文章列表
export function getArticleListApi(params) {
    return uni.$u.http.get('article/lists', params)
}


// 获取文章详情
export function getArticleDetailApi(params) {
    return uni.$u.http.get('article/detail', params)
}
