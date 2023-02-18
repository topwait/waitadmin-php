<template>
    <view class="layout-detail-widget">
        <view class="header">
            <view class="pb-30">{{ detail.title }}</view>
            <view class="flex justify-between">
                <view class="text-xs font-thin color-muted">发布时间: {{ detail.create_time }}</view>
                <view class="text-xs font-thin color-muted">{{ detail.browse }}人浏览</view>
            </view>
        </view>
        <view class="content">
			<u-parse :html="detail.content"></u-parse>
        </view>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import { getArticleDetailApi } from '@/api/articleApi'

const detail = ref({})

onLoad((options) => {
    queryArticleDetail(parseInt(options.id))
})

const queryArticleDetail = async (id) => {
    const { data } = await getArticleDetailApi({ id })
    detail.value = data
}
</script>

<style lang="scss">
page { background: #ffffff; }
.layout-detail-widget {
    .header {
        font-size: 36rpx;
        font-weight: bold;
        color: #333333;
        padding: 30rpx;
        border-bottom: 1rpx solid #f2f2f2;
    }
    .content {
        margin: 30rpx 20rpx;
    }
}
</style>
