<template>
    <view :class="themeName">
        <view class="layout-about-widget">
            <view class="icon">
                <u-icon name="star-fill" size="100" color="#fff" />
            </view>
            <view class="version">v1.3.0</view>
        </view>
    </view>
</template>

<script setup>
</script>

<style lang="scss">
.layout-about-widget {
    display: flex;
    align-items: center;
    flex-direction: column;
    padding-top: 200rpx;
    .icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 180rpx;
        height: 180rpx;
        border-radius: 14rpx;
        background-color: var(--theme-color);
    }
    .version {
        padding-top: 20rpx;
        font-size: 30rpx;
        font-weight: 500;
        color: #333333;
    }
}
</style>
