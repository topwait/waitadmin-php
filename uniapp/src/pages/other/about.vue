<template>
    <view class="layout-about-widget">
        <view class="icon">
            <u-icon name="star-fill" size="100" color="#fff"></u-icon>
        </view>
        <view class="version">v1.3.0</view>
    </view>
</template>

<script lang="scss">

</script>

<style lang="scss">
.layout-about-widget {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 200rpx;
    .icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 180rpx;
        height: 180rpx;
        border-radius: 14rpx;
        background-color: #2979ff;
    }
    .version {
        font-size: 30rpx;
        font-weight: 500;
        color: #333333;
        padding-top: 20rpx
    }
}
</style>
