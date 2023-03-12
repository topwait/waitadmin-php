<template>
    <!-- 轮播图片 -->
    <view class="banner">
        <view class="diy-swiper">
            <image class="bgImage" src="http://admin.waitshop.cn/storage/attach/image/20230308/18322503a9af8f575420ea8e06988.png"/>
            <u-swiper
                :list="banner"
                class="swiper"
                mode="round"
                height="300"
                :bg-color="'0'"
            />
        </view>
    </view>

    <!-- 推荐服务 -->
    <w-service grid="25%" :list="orders" />

    <!-- 最新资讯 -->
    <view class="layout-news-widget">
        <view class="title">最新资讯</view>
        <view class="list">
            <view v-for="(item, index) in article" :key="index" class="item" @click="$go('/pages/article/detail?id='+item.id)">
                <u-image :lazy-load="true" border-radius="4" width="240rpx" height="180rpx" :src="item.image" style="flex-shrink: 0;" />
                <view class="flex flex-col justify-between px-20">
                    <view class="truncate-line-1 text-xl color-main font-medium">{{ item.title }}</view>
                    <view class="truncate-line-1 text-xs color-text">{{ item.intro }}</view>
                    <view class="flex justify-between">
                        <view class="text-xs color-muted">{{ item.create_time }}</view>
                        <view class="text-xs color-muted">{{ item.browse}}人浏览</view>
                    </view>
                </view>
            </view>
        </view>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { getIndexApi } from '@/api/indexApi'

const article = ref([])
const banner = ref([
    {
        image: 'https://b2ctests.waitshop.cn/static/images/diy/client/index/banner01.png',
        title: '昨夜星辰昨夜风，画楼西畔桂堂东'
    },
    {
        image: 'https://b2ctests.waitshop.cn/static/images/diy/client/index/banner02.png',
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

onShow(async () => {
    const data = await getIndexApi()
    article.value = data.article
})
</script>

<style lang="scss">
.banner {
    position: relative;
   .bgImage { width: 100%; height: 300rpx; }
   .diy-swiper {
   		position: relative;
   		height: 303rpx;
   		.swiper {
   			position: absolute;
   			top: 0;
   			left: 0;
   			right: 0;
   			padding: 0 20rpx;
   		}
   	}
}
    
.layout-news-widget {
    margin: 20rpx;
    border-radius: 14rpx;
    background-color: #ffffff;
    .title {
        font-size: 34rpx;
        font-weight: bold;
        color: #333333;
        padding: 30rpx 0 10rpx 20rpx;
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
