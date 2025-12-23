<template>
    <w-loading :show="isFirstLoading" />
    <view class="flex-1 p-3 bg-page">
        <mp-html :content="content" />
    </view>
</template>

<script setup lang="ts">
import indexApi from '@/api/index'
import mpHtml from '@/uni_modules/mp-html/components/mp-html/mp-html.vue'

// 首次加载
const isFirstLoading = ref<boolean>(true)

// 协议内容
const content = ref<string>('')

// 获取协议
const queryPolicy = async (type: string) => {
    try {
        const data = await indexApi.policy(type)
        content.value = data.content
        isFirstLoading.value = false
    } catch {

    } finally {
        isFirstLoading.value = false
    }
}

onLoad((options: any) => {
    switch (options.type) {
        case 'service':
            uni.setNavigationBarTitle({title: '用户协议'})
            queryPolicy('service')
            break
        case 'privacy':
            uni.setNavigationBarTitle({title: '隐私政策'})
            queryPolicy('privacy')
            break
        case 'payment':
            uni.setNavigationBarTitle({title: '支付协议'})
            queryPolicy('payment')
            break
        default:
            uni.setNavigationBarTitle({title: '用户协议'})
            queryPolicy('service')
    }
})
</script>
