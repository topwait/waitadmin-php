<template>
    <view>
        <view class="layout-header-widget">
            <view class="grid-skinny-unit">
                <view class="flex items-center">
                    <u-avatar :src="userInfo.avatar" mode="circle" size="100" class="h-100" />
                    <view v-if="isLogin" class="synopsis">
                        <view class="text-lg color-white">{{ userInfo.nickname }}</view>
                        <view class="text-xs color-lighter">ID: {{ userInfo.sn }}</view>
                    </view>
                    <view v-else class="login" @click="onJump">点击登录</view>
                </view>
                <view class="flex items-start">
                    <u-icon class="icon" name="bell" color="#ffffff" size="42" />
                    <u-icon class="icon" name="setting" color="#ffffff" size="42" />
                </view>
            </view>
        </view>

       <w-service title="" grid="20%" :list="orders" />

        <w-service title="我的服务" mod="col" grid="25%" :list="service" />
    </view>
</template>

<script setup>
import { reactive } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()
const isLogin  = userStore.$state.isLogin
const userInfo = userStore.$state.userInfo

console.log(isLogin)

onShow(() => {
    userStore.getUserInfo()
})

const orders = reactive([
    {
        'name': '待付款',
        'image': '../../../static/tabBar/tab_category_no.png'
    },
    {
        'name': '待发货',
        'image': '../../../static/tabBar/tab_user_no.png'
    },
    {
        'name': '待收货',
        'image': '../../../static/tabBar/tab_cart_no.png'
    },
    {
        'name': '评价',
        'image': '../../../static/tabBar/tab_home_no.png'
    },
    {
        'name': '退款售后',
        'image': '../../../static/tabBar/tab_home_no.png'
    }
])

const service = reactive([
    {
        'name': '发票管理',
        'image': '../../../static/tabBar/tab_category_no.png'
    },
    {
        'name': '我的管理',
        'image': '../../../static/tabBar/tab_user_no.png'
    },
    {
        'name': '推广管理',
        'image': '../../../static/tabBar/tab_cart_no.png'
    },
    {
        'name': '调整管理',
        'image': '../../../static/tabBar/tab_home_no.png'
    },
    {
        'name': '订单管理',
        'image': '../../../static/tabBar/tab_home_no.png'
    },
    {
        'name': '内容管理',
        'image': '../../../static/tabBar/tab_home_no.png'
    }
])

const onJump = () => {
    uni.navigateTo({
        url: '/pages/users/login/login'
    })
}

</script>

<style lang="scss">
.layout-header-widget {
    min-height: 160rpx;
    background: url("/static/bg_user.png");
    background-position: center right;
    background-repeat: no-repeat;
    background-size: auto 100%;
    background-color: $uni-bg-theme;
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
        .login {
            margin-left: 20rpx;
            padding: 8rpx;
            width: 170rpx;
            font-size: 28rpx;
            border: 2rpx solid #ffffff;
            border-radius: 50rpx;
            text-align: center;
            color: #ffffff;
        }
        .icon {
            margin-top: 14rpx;
            margin-right: 20rpx;
        }
    }
}
</style>
