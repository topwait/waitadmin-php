<template>
    <view :class="themeName">
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
import ArticleApi from '@/api/ArticleApi'

const detail = ref({})

onLoad((options) => {
    queryArticleDetail(parseInt(options.id))
})

const queryArticleDetail = async (id) => {
    detail.value = await ArticleApi.detail({ id })
}
</script>

<style lang="scss">
page { background: #ffffff; }
.layout-detail-widget {
    .header {
        padding: 30rpx;
        font-size: 36rpx;
        font-weight: bold;
        border-bottom: 1rpx solid #f2f2f2;
        color: #333333;
    }
    .content {
        margin: 30rpx 20rpx;
    }
}
</style>
