<template>
    <view class="pt-60">
        <view class="layout-service-widget">
            <view class="flex flex-col items-center">
                <u-image class="u-margin-top-60" width="390rpx" height="390rpx" :src="''"></u-image>
                <view class="u-font-32 u-font-weight u-margin-top-20">扫码添加客服</view>
                <view class="info">
                    <view class="line">服务时间：PM 18:00 </view>
                    <view class="line">服务电话：18927154977</view>
                    <view class="line">服务Q Q：2474369941</view>
                </view>
            </view>
            <view class="pt-40 px-60">
                 <w-button >保存二维码</w-button>
            </view>
            
        </view>
    </view>
</template>

<script setup>

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
