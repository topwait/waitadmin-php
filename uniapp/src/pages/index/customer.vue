<template>
    <view :class="themeName">
        <view class="pt-60">
            <view class="layout-service-widget">
                <view class="flex flex-col items-center">
                    <u-image class="mt-60" width="390rpx" height="390rpx" :src="diyItems.image" />
                    <view class="mt-20 font-xl font-weight-bold">{{ diyItems.title }}</view>
                    <view class="info">
                        <view v-if="diyItems.datetime" class="line">服务时间：{{ diyItems.datetime }} </view>
                        <view v-if="diyItems.mobile" class="line">服务电话：{{ diyItems.mobile }}</view>
                        <view v-if="diyItems.qq" class="line">服务Q Q：{{ diyItems.qq }}</view>
                    </view>
                </view>
                <view class="pt-40 px-60">
                    <u-button
                        type="theme"
                        shape="circle"
                    >保存二维码</u-button>
                </view>
            </view>
        </view>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import designApi from '@/api/designApi'

const diyItems = ref({})

onShow(async () => {
    diyItems.value = await designApi.diyTie()
})
</script>

<style lang="scss" scoped>
.layout-service-widget {
    margin: 0 60rpx;
    padding-top: 40rpx;
    height: 900rpx;
    border-radius: 14rpx;
    background-color: #ffffff;
    .info {
        margin-top: 40rpx;
        .line {
            font-size: 26rpx;
            font-weight: 400;
            color: #333333;
            line-height: 46rpx;
        }
    }
}
</style>
