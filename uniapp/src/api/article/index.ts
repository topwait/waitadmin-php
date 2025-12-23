import request from '@/utils/request'

const articleApi = {
    /**
     * 文章分类
     *
     * @return {ArticleCategoryResponse[]}
     * @author zero
     */
    category(): Promise<ArticleCategoryResponse[]> {
        return request.get<ArticleCategoryResponse[]>({
            url: '/article/category'
        })
    },

    /**
     * 文章列表
     *
     * @param params
     * @return {ArticleListsResponse[]}
     * @author zero
     */
    lists(params: any): Promise<ArticleListsResponse[]> {
        return request.get<ArticleListsResponse[]>({
            url: '/article/lists',
            params
        })
    },

    /**
     * 文章详情
     *
     * @param {number} id
     * @return {ArticleDetailResponse}
     * @author zero
     */
    detail(id: number): Promise<ArticleDetailResponse> {
        return request.get<ArticleDetailResponse>({
            url: '/article/detail',
            params: {
                id
            }
        })
    }
}

export default articleApi
