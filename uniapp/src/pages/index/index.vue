<template>
    <view >
        <view class="banner">
            <u-swiper
                :list="banner"
                mode='round'
                height="300"
            />
        </view>
        
        <w-service title="" grid="25%" :list="orders" />
        
        <view class="layout-news-widget">
            <view v-for="(item, index) in 10" :key="index" class="item" @click="$go('/pages/article/detail?id='+item.id)">
                <u-image :lazy-load="true" width="240rpx" height="180rpx" :src="'https://cdn.uviewui.com/uview/swiper/1.jpg'" style="flex-shrink: 0;" />
                <view class="flex flex-col justify-between px-20">
                    <view class="truncate-line-1 text-xl color-main font-medium">呱呱呱呱呱呱呱呱呱</view>
                    <view class="truncate-line-1 text-xs color-text">gggggggg</view>
                    <view class="flex justify-between">
                        <view class="text-xs color-muted">2022-09-30 11:32:01</view>
                        <view class="text-xs color-muted">45人浏览</view>
                    </view>
                </view>
            </view>
        </view>
     </view>
</template>

<script setup>
import { ref } from 'vue'
import { onShow } from '@dcloudio/uni-app'

const banner = ref([
    {
        image: 'https://cdn.uviewui.com/uview/swiper/1.jpg',
        title: '昨夜星辰昨夜风，画楼西畔桂堂东'
    },
    {
        image: 'https://cdn.uviewui.com/uview/swiper/2.jpg',
        title: '身无彩凤双飞翼，心有灵犀一点通'
    },
])

const orders = ref([
    {
        'name': '资讯中心',
        'image': '/static/send.png'
    },
    {
        'name': '我的收藏',
        'image': '/static/tou.png'
    },
    {
        'name': '个人设置',
        'image': '/static/serve.png'
    },
    {
        'name': '关于我们',
        'image': '/static/tuijain.png'
    }
])
</script>

<style lang="scss">
.layout-news-widget {
    margin: 20rpx;
    border-radius: 14rpx;
    background-color: #ffffff;
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
