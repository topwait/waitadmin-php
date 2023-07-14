<template>
    <view :class="themeName">
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

        <w-service
            :mod="diyService.base?.layout"
            :title="diyService.base?.title"
            :grid="diyService.base?.layout"
            :list="diyService?.list"
        />

        <w-adv :list="diyAdv?.list" />
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/userStore'
import DesignApi from '@/api/DesignApi'
import UserApi from '@/api/userApi'

const userStore = useUserStore()
const { isLogin } = storeToRefs(userStore)

const userInfo = ref({})
const diyAdv = ref({})
const diyService = ref({})

onShow(async () => {
    try {
        const diyItems = await DesignApi.diyMe()
        diyAdv.value = diyItems.adv
        diyService.value = diyItems.service

        if (isLogin.value) {
            userInfo.value = await UserApi.center()
        }
    } catch (e) {
        return false
    }
})
</script>

<style lang="scss">
.layout-header-widget {
    min-height: 180rpx;
    background: url("/static/bg_head_radiant.png");
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
