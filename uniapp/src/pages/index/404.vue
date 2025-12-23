<template>
    <view class="flex-1 flex items-center justify-center">
        <view class="text-center">
            <wd-icon
                name="close-outline"
                size="180rpx"
                color="var(--text-color-secondary)"
            />

            <view class="mt-3 text-tx-secondary">
                ~ 您访问的页面不存在 ~
            </view>

            <view class="mt-8">
                <wd-button
                    type="info"
                    plain
                    @click="$go('/pages/index/index', 'reLaunch')"
                >
                    回到首页
                </wd-button>
            </view>
        </view>
    </view>
</template>
