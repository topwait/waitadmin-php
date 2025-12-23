<template>
    <z-paging
        ref="pagingRef"
        v-model="dataLists"
        auto-show-back-to-top
        :auto="false"
        :data-key="0"
        :fixed="false"
        height="100%"
        @query="queryArticleList"
    >
        <view class="mt-2.5 bg-overlay">
            <view
                v-for="(item, index) in dataLists"
                :key="index"
                class="flex p-2.5 border-b border-br-thinned last:border-b-0"
                @click="$go('/pages/article/detail?id='+item.id)"
            >
                <wd-img
                    :lazy-load="true"
                    width="240rpx"
                    height="180rpx"
                    :radius="4"
                    :src="item.image"
                    style="flex-shrink: 0;"
                />
                <view class="flex flex-1 flex-col justify-between px-4 overflow-hidden">
                    <view class="truncate text-xl font-medium text-tx-primary">
                        {{ item.title }}
                    </view>
                    <view class="line-clamp-2 text-xs text-tx-regular">
                        {{ item.intro }}
                    </view>
                    <view class="flex justify-between text-tx-placeholder">
                        <view class="text-xs">{{ item.create_time }}</view>
                        <view class="text-xs">{{ item.browse }}人浏览</view>
                    </view>
                </view>
            </view>
        </view>
    </z-paging>
</template>

<script lang="ts" setup>
import articleApi from '@/api/article'

// 接收参数
const props = defineProps({
    // 分类ID
    cid: {
        type: Number,
        default: 0
    },
    // 搜索关键词
    keyword: {
        type: String,
        default: ''
    },
    // 选项卡下标
    tabIndex: {
        type: Number,
        default: 0
    },
    // 滑动器下标
    swiperIndex: {
        type: Number,
        default: 0
    }
})

// 首次加载状态
const isFirstLoad = ref<boolean>(false)
// 分页组件引用
const pagingRef = ref<any>(null)
// 文章数据列表
const dataLists = ref<ArticleListsResponse[]>([])

/**
 * 获取文章列表
 *
 * @param {number} pageNo
 * @author zero
 */
const queryArticleList = async (pageNo: number) => {
    articleApi.lists({
        cid: props.cid,
        pageNo: pageNo,
        keyword: props.keyword
    }).then((res: any) => {
        pagingRef.value?.complete(res.data)
        isFirstLoad.value = true
        uni.hideLoading()
    }).catch(() => {
        pagingRef.value?.complete(false)
    })
}

/**
 * 监听分类切换
 */
watch(
    () => props.tabIndex,
    async (value: number) => {
        await nextTick()
        if (value === props.swiperIndex && !isFirstLoad.value) {
            await uni.showLoading({
                title: '加载中...'
            })
            setTimeout(() => {
                pagingRef.value?.reload()
            }, 5)
        }
    },
    { immediate: true }
)

/**
 * 监听文章搜索
 */
watch(
    () => props.keyword,
    async () => {
        await nextTick()
        pagingRef.value?.reload()
    }
)
</script>
