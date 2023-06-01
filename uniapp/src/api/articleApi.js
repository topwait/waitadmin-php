export default class {
    /**
     * 分类类别
     */
    static category() {
        return uni.$u.http.get('article/category')
    }

    /**
     * 文章列表
     */
    static lists(params) {
        return uni.$u.http.get('article/lists', params)
    }

    /**
     * 文章详情
     */
    static detail({ id }) {
        return uni.$u.http.get('article/detail', { id })
    }
}
