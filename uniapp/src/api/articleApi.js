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
        let param = { cid: params.cid }
        if (params.pageNo) {
            param.page = params.pageNo
        }
        if (params.keyword) {
            param.keyword = params.keyword
        }
        return uni.$u.http.get('article/lists', param)
    }

    /**
     * 文章详情
     */
    static detail(id) {
        return uni.$u.http.get('article/detail', { id })
    }
}
