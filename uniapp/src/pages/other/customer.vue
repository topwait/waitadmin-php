<template>
    <view class="pt-60">
        <view class="layout-service-widget">
            <view class="flex flex-col items-center">
                <u-image class="u-margin-top-60" width="390rpx" height="390rpx" :src="diyItems.image"></u-image>
                <view class="u-font-32 u-font-weight u-margin-top-20">{{ diyItems.title }}</view>
                <view class="info">
                    <view class="line" v-if="diyItems.datetime">服务时间：{{ diyItems.datetime }} </view>
                    <view class="line" v-if="diyItems.mobile">服务电话：{{ diyItems.mobile }}</view>
                    <view class="line" v-if="diyItems.qq">服务Q Q：{{ diyItems.qq }}</view>
                </view>
            </view>
            <view class="pt-40 px-60">
                 <w-button >保存二维码</w-button>
            </view>
        </view>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { diyTieApi } from '@/api/diyApi'

const diyItems = ref({})

onShow(async () => {
    diyItems.value = await diyTieApi()
})
</script>

<style lang="scss">
.layout-service-widget {
    margin: 0 60rpx;
    padding-top: 40rpx;
    height: 900rpx;
    border-radius: 14rpx;
    background-color: #FFF;
    .info {
        margin-top: 40rpx;
        .line {
            color: #333;
            line-height: 46rpx;
            font-size: 26rpx;
            font-weight: 400;
        }
    }
}
</style>
