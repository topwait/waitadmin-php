/** 文章分类响应 */
interface ArticleCategoryResponse {
    id: number;
    name: string;
}

/** 文章列表响应 */
interface ArticleListsResponse {
    id: number;
    image: string;
    title: string;
    intro: string;
    browse: string;
    create_time: string;
}

/** 文章详情响应 */
interface ArticleDetailResponse {
    id: number;
    title: string;
    intro: string;
    content: string;
    browse: string;
    create_time: string;
}
