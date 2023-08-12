<template>
    <view :class="themeName">
        <view class="layout-policy-widget">
            <u-parse :html="content" />
        </view>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import indexApi from '@/api/indexApi'

const content = ref('')

onLoad((options) => {
    switch (options.type) {
        case 'service':
            uni.setNavigationBarTitle({title: '服务协议'})
            queryPolicy('service')
            break
        case 'privacy':
            uni.setNavigationBarTitle({title: '隐私政策'})
            queryPolicy('privacy')
            break
        default:
            uni.setNavigationBarTitle({title: '政策协议'})
    }
})

const queryPolicy = async (type) => {
    try {
        const data = await indexApi.policy({ type })
        content.value = data.content
    } catch (e) {
        return false
    }
}
</script>

<style lang="scss">
page {
    background-color: #ffffff;
    .layout-policy-widget {
        padding: 20rpx 10rpx;
    }
}
</style>