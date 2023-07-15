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
        const param = {
            cid: params.cid || 0,
            pageNo: params.pageNo || 1,
            pageSize: params.pageSize || 10
        }
        return uni.$u.http.get('article/lists', param)
    }

    /**
     * 文章详情
     */
    static detail({ id }) {
        return uni.$u.http.get('article/detail', { id })
    }
}
