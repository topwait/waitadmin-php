<template>
    <view class="layout-policy-widget">
        <u-parse :html="content"></u-parse>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'

const content = '呵护额和未婚夫和文化'

onLoad((options) => {
    switch (options.type) {
        case 'service':
            uni.setNavigationBarTitle({title: '服务协议'})
            break
        case 'privacy':
            uni.setNavigationBarTitle({title: '隐私政策'})
            break
        case 'ensure':
            uni.setNavigationBarTitle({title: '售后保障'})
            break
        default:
            uni.setNavigationBarTitle({title: '政策协议'})
    }
})

</script>

<style lang="scss">
page {
    background-color: #ffffff;
}
.layout-policy-widget {
    padding: 20rpx 10rpx;
}
</style>