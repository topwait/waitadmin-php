<template>
    <view :class="themeName">
        <!-- 首次加载 -->
        <w-loading v-if="isFirstLoading" />

        <!-- 协议内容 -->
        <view class="layout-policy-widget">
            <u-parse :html="content" />
        </view>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import indexApi from '@/api/indexApi'

// 首次加载
const isFirstLoading = ref(true)

// 协议内容
const content = ref('')

// 获取协议
const queryPolicy = async (type) => {
    try {
        const data = await indexApi.policy(type)
        content.value = data.content
        isFirstLoading.value = false
    } catch (e) {
        return false
    }
}

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
</script>

<style lang="scss" scoped>
page {
    background-color: #ffffff;
    .layout-policy-widget {
        padding: 20rpx 10rpx;
    }
}
</style>