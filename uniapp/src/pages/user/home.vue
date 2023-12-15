<template>
    <view :class="themeName">
        <!-- 首次加载 -->
        <w-loading v-if="isFirstLoading" />

        <!-- 用户信息 -->
        <view class="layout-header-widget">
            <view class="grid-skinny-unit">
                <view class="flex items-center">
                    <u-avatar :src="userInfo.avatar" mode="circle" size="100" class="h-100" />
                    <view v-if="isLogin" class="synopsis">
                        <view class="font-lg color-white">{{ userInfo.nickname }}</view>
                        <view class="font-xs color-lighter">ID: {{ userInfo.sn }}</view>
                    </view>
                    <view v-else class="login" @tap="$go('/pages/login/enroll')">点击登录</view>
                </view>
                <view class="flex items-start">
                    <u-icon class="icon" name="setting" color="#ffffff" size="42" @tap="$go('/pages/user/intro')" />
                </view>
            </view>
        </view>

        <!-- 用户服务 -->
        <w-service
            :mod="diyService.base?.layout"
            :title="diyService.base?.title"
            :grid="diyService.base?.layout"
            :list="diyService?.list"
        />

        <!-- 轮播广告 -->
        <w-adv :list="diyAdv?.list" />
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/userStore'
import designApi from '@/api/designApi'
import userApi from '@/api/userApi'

// 首次加载
const isFirstLoading = ref(true)

// 登录状态
const userStore = useUserStore()
const { isLogin } = storeToRefs(userStore)

// 参数定义
const userInfo = ref({})   // 用户信息
const diyService = ref({}) // 服务工具
const diyAdv = ref({})     // 轮播广告

onShow(async () => {
    const diyItems = await designApi.diyMe()
    diyAdv.value = diyItems.adv
    diyService.value = diyItems.service

    if (isLogin.value) {
        userInfo.value = await userApi.center()
    }
    isFirstLoading.value = false
})
</script>

<style lang="scss" scoped>
.layout-header-widget {
    min-height: 180rpx;
    background: url("../../static/bg_head_radiant.png");
    background-position: center right;
    background-repeat: no-repeat;
    background-size: auto 100%;
    background-color: var(--theme-background);
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
            color: #ffffff;
            border: 2rpx solid #ffffff;
            border-radius: 50rpx;
            text-align: center;
        }
        .icon {
            margin-top: 14rpx;
            margin-right: 20rpx;
        }
    }
}
</style>
