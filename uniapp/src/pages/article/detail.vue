<template>
    <view :class="themeName">
        <!-- 首次加载 -->
        <w-loading v-if="isFirstLoading" />

        <!-- 文章详情 -->
        <view class="layout-detail-widget">
            <view class="header">
                <view class="pb-30">{{ detail.title }}</view>
                <view class="flex justify-between">
                    <view class="font-xs font-weight-thin color-main">发布时间: {{ detail.create_time }}</view>
                    <view class="font-xs font-weight-thin color-main">{{ detail.browse }}人浏览</view>
                </view>
            </view>
            <view class="content">
                <u-parse :html="detail.content" />
            </view>
        </view>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import articleApi from '@/api/articleApi'

// 首次加载
const isFirstLoading = ref(true)

// 文章详情
const detail = ref({})

onLoad(async (options) => {
    const id = parseInt(options.id)
    detail.value = await articleApi.detail(id)
    isFirstLoading.value = false
})
</script>

<style lang="scss">
page { background: #ffffff; }
.layout-detail-widget {
    .header {
        padding: 30rpx;
        font-size: 36rpx;
        font-weight: bold;
        color: #333333;
        border-bottom: 1rpx solid #f2f2f2;
    }
    .content {
        margin: 30rpx 20rpx;
        padding-bottom: 10rpx;
    }
}
</style>
