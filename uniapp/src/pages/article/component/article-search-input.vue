<template>
    <view class="layout-input-widget">
        <view class="grid-search-box">
            <u-icon name="search" color="#999" size="30" class="icon" />
            <u-input type="text"  class="input" placeholder="请输入搜索关键词" />
        </view>
        
    </view>
</template>

<script setup>

</script>

<style lang="scss">
.layout-input-widget {
    padding: 0 20rpx;
    padding-bottom: 12rpx;
    border-bottom: 1px solid #f2f2f2;
    background-color: #ffffff;
    .grid-search-box {
        display: flex;
        padding: 0 20rpx;
        border-radius: 50rpx;
        background-color: #f5f5f5;
        .icon {
            padding-right: 14rpx;
        }
    }

}
</style>
