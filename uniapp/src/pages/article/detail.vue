<template>
    <w-loading :show="isFirstLoading" />
    <view class="flex-1 bg-page">
        <view class="p-3.5 border-b border-lighter">
            <view class="pb-2 text-3xl font-bold text-tx-primary">
                {{ detail.title }}
            </view>
            <view class="flex justify-between text-xs text-tx-placeholder">
                <view>发布时间: {{ detail.create_time }}</view>
                <view>{{ detail.browse }}人浏览</view>
            </view>
        </view>
        <view class="content pb-1 m-3.5">
            <mp-html :content="detail.content" />
        </view>
    </view>
</template>

<script setup lang="ts">
import articleApi from '@/api/article'
import mpHtml from '@/uni_modules/mp-html/components/mp-html/mp-html.vue'

// 首次加载
const isFirstLoading = ref<boolean>(true)

// 文章详情
const detail = ref({} as ArticleDetailResponse)

onLoad(async (options: any) => {
    const id = parseInt(options.id)
    detail.value = await articleApi.detail(id)
    isFirstLoading.value = false
})
</script>
