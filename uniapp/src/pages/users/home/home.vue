<template>
    <view>
        <view class="layout-header-widget">
            <view class="grid-skinny-unit">
                <view class="flex items-center">
                    <u-avatar :src="'/src/static/logo.png'" mode="circle" size="100" class="h-100" />
                    <view class="synopsis">
                        <view class="text-lg color-white">相见不如怀念</view>
                        <view class="text-xs color-lighter">ID: u7456565</view>
                    </view>
                </view>
                <view class="flex items-start">
                    <u-icon class="icon" name="bell" color="#ffffff" size="42" />
                    <u-icon class="icon" name="setting" color="#ffffff" size="42" />
                </view>
            </view>
        </view>

        <w-service title="我的服务" grid="25%" :list="service" />
    </view>
</template>

<script setup>
import { reactive } from 'vue'

const service = reactive([
    {
        "name": "发票管理",
        "image": "../../../static/tabBar/tab_category_no.png"
    },
    {
        "name": "我的管理",
        "image": "../../../static/tabBar/tab_user_no.png"
    },
    {
        "name": "推广管理",
        "image": "../../../static/tabBar/tab_cart_no.png"
    },
    {
        "name": "调整管理",
        "image": "../../../static/tabBar/tab_home_no.png"
    },
    {
        "name": "订单管理",
        "image": "../../../static/tabBar/tab_home_no.png"
    },
    {
        "name": "内容管理",
        "image": "../../../static/tabBar/tab_home_no.png"
    }
])
</script>

<style lang="scss">
.layout-header-widget {
    min-height: 160rpx;
    background: url("/static/bg_user.png");
    background-position: center right;
    background-repeat: no-repeat;
    background-size: auto 100%;
    background-color: $uni-bg-primary;
    .grid-skinny-unit {
        display: flex;
        justify-content: space-between;
        padding: 30rpx;
        .synopsis {
            display: flex;
            flex: 1;
            flex-direction: column;
            justify-content: space-between;
            margin-left: 20rpx;
            padding: 10rpx 0;
            height: 100%;
        }
        .icon {
            margin-top: 14rpx;
            margin-right: 20rpx;
        }
    }
}
</style>
