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
                    <view v-else class="login" @tap="$go('/pages/login/enroll')">点击登录</view>
                </view>
                <view class="flex items-start">
                    <u-icon class="icon" name="setting" color="#ffffff" size="42" @tap="$go('/pages/users/intro')" />
                </view>
            </view>
        </view>

        <w-service title="我的服务" mod="col" grid="25%" :list="service" />
    </view>
</template>

<script setup>
import { reactive } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()
const { userInfo, isLogin } = storeToRefs(userStore)

onShow(() => {
    userStore.getUserInfo()
})

const service = reactive([
    {
        'name': '我的收藏',
        'image': '/static/tools_collect.png'
    },
    {
        'name': '个人设置',
        'image': '/static/tools_help.png'
    },
    {
        'name': '联系客服',
        'image': '/static/tools_service.png'
    },
    {
        'name': '关于我们',
        'image': '/static/tools_team.png'
    }
])
</script>

<style lang="scss">
.layout-header-widget {
    min-height: 180rpx;
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
