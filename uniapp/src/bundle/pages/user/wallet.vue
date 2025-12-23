<template>
    <view class="p-3">
        <!-- 余额汇总 -->
        <view class="layout-balance-widget">
            <view class="text-xs">余额（CNY）</view>
            <view class="pt-2.5">{{ detail.wallet }}</view>
            <view class="flex justify-between pt-3">
                <view class="w-[50%]">
                    <view class="text-xs">累计充值(元)</view>
                    <view class="text-3xl mt-1">{{ detail.total_recharge_amount }}</view>
                </view>
                <view class="w-[50%]">
                    <view class="text-xs">累计消费(元）</view>
                    <view class="text-3xl mt-1">{{ detail.total_order_amount }}</view>
                </view>
            </view>
        </view>
    </view>
</template>

<script setup lang="ts">
interface detailVo {
    wallet: number;
    total_recharge_amount: number;
    total_order_amount: number;
}

const detail = ref<detailVo>({
    wallet: 100.95,
    total_recharge_amount: 189.52,
    total_order_amount: 11.61
})
</script>

<style lang="scss">
// 余额汇总样式
.layout-balance-widget {
    height: 330rpx;
    padding: 30rpx;
    font-size: 76rpx;
    color: var(--color-white);
    background: linear-gradient(180deg, var(--color-primary) 0%, var(--color-primary) 100%);
    border-radius: 20rpx;
}
</style>
