<template>
    <view :class="themeName">
        <!-- 首次加载 -->
        <w-loading v-if="isFirstLoading" />

        <!-- 轮播图片 -->
        <view class="layout-banner-widget">
            <view class="diy-swiper">
                <view class="backdrop" />
                <u-swiper
                    :list="diyItem?.banner"
                    class="swiper"
                    mode="round"
                    height="300"
                    :bg-color="'0'"
                />
            </view>
        </view>

        <!-- 推荐服务 -->
        <w-service grid="25%" :list="diyItem.nav" />

        <!-- 最新资讯 -->
        <view class="layout-news-widget">
            <view class="title">最新资讯</view>
            <view class="list">
                <view v-for="(item, index) in article" :key="index" class="item" @click="$go('/pages/article/detail?id='+item.id)">
                    <u-image
                        :lazy-load="true"
                        border-radius="4"
                        width="240rpx"
                        height="180rpx"
                        :src="item.image"
                        style="flex-shrink: 0;"
                    />
                    <view class="flex flex-col justify-between px-20">
                        <view class="truncate-line-1 font-xl font-weight-medium color-main">{{ item.title }}</view>
                        <view class="truncate-line-2 font-xs color-text">{{ item.intro }}</view>
                        <view class="flex justify-between">
                            <view class="font-xs color-muted">{{ item.create_time }}</view>
                            <view class="font-xs color-muted">{{ item.browse }}人浏览</view>
                        </view>
                    </view>
                </view>
            </view>
        </view>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import designApi from '@/api/designApi'
import indexApi from '@/api/indexApi'

const isFirstLoading = ref(true)
const diyItem = ref([])
const article = ref([])

onShow(async () => {
    const results = await indexApi.index()
    article.value = results.article
    diyItem.value = await designApi.diyIndex()
    isFirstLoading.value = false
})
</script>

<style lang="scss" scoped>
.layout-banner-widget {
    position: relative;
    .backdrop {
        height: 300rpx;
        background-repeat: round;
        background-size: contain;
        background-color: var(--theme-background);
        background-image: url("../../static/bg_head_honour.png");
    }
    .diy-swiper {
        position: relative;
        height: 300rpx;
        .swiper {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            padding: 0 20rpx;
        }
    }
    // #ifdef H5
    .diy-swiper { height: 320rpx;  }
    .diy-swiper .swiper { padding-top: 20rpx; }
    // #endif
}
.layout-news-widget {
    margin: 20rpx;
    border-radius: 14rpx;
    background-color: #ffffff;
    .title {
        padding: 30rpx 0 10rpx 20rpx;
        font-size: 34rpx;
        font-weight: bold;
        color: #333333;
    }
    .item {
        display: flex;
        flex: 1;
        padding: 20rpx;
        border-bottom: 1rpx dashed #f2f2f2;
        &:last-child {
            border-bottom: 0;
        }
    }
}
</style>
