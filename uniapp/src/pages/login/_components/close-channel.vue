<template>
    <view class="flex-1 flex items-center justify-center">
        <view class="flex flex-col items-center">
            <wd-icon
                name="info-circle"
                size="180rpx"
                color="var(--text-color-secondary)"
            />
            <view class="mt-2 text-tx-secondary">
                {{ message }}
            </view>
            <view class="mt-6">
                <wd-button
                    plain
                    @click="$go('/pages/index/index', 'reLaunch')"
                >
                    回到首页
                </wd-button>
            </view>
        </view>
    </view>
</template>

<script setup lang="ts">
defineProps({
    message: {
        type: String,
        default: '渠道尚未开放'
    }
})
</script>
