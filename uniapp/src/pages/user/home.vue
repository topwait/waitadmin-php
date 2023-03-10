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
import { reactive, ref } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/userStore'
import { userDesignApi, userCenterApi } from '@/api/userApi'

const userStore = useUserStore()
const { isLogin } = storeToRefs(userStore)

const userInfo = ref({})
const diyAdv = ref({})
const diyService = ref({})

const list = ref([{
        image: 'https://cdn.uviewui.com/uview/swiper/1.jpg',
        title: '昨夜星辰昨夜风，画楼西畔桂堂东'
    },
    {
        image: 'https://cdn.uviewui.com/uview/swiper/2.jpg',
        title: '身无彩凤双飞翼，心有灵犀一点通'
    },
    {
        image: 'https://cdn.uviewui.com/uview/swiper/3.jpg',
        title: '谁念西风独自凉，萧萧黄叶闭疏窗，沉思往事立残阳'
    }
])

onShow(async () => {
    const diyItems = await userDesignApi()
    diyAdv.value = diyItems.adv
    diyService.value = diyItems.service
    
    if (isLogin.value) {
        userInfo.value = await userCenterApi()
    }
})
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
