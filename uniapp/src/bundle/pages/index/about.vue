<template>
    <view class="flex flex-col items-center justify-center pt-26">
        <view
            class="flex items-center justify-center
                   w-22 h-22 rounded-xl bg-primary"
        >
            <wd-icon
                name="star-filled"
                color="#ffffff"
                size="100rpx"
            />
        </view>
        <view class="pt-4 text-lg text-tx-primary">
            {{ config.version }}
        </view>
    </view>
</template>

<script setup lang="ts">
import config from '@/config/index'
</script>
