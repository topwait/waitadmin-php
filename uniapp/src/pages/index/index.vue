<template>
    <view class="content">
        <image class="logo" src="/static/logo.png" />
        <view class="text-area">
            <text class="title">{{ title }}</text>
        </view>
    </view>
</template>

<script setup>
import { ref } from 'vue'

let title = ref('Hello World!')
</script>

<style lang="scss">
.content {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
.logo {
    margin: 0 auto;
    margin-top: 200rpx;
    margin-bottom: 50rpx;
    width: 200rpx;
    height: 200rpx;
}
.text-area {
    display: flex;
    justify-content: center;
    .title {
        font-size: 50rpx;
    }
}
.title {
    font-size: 36rpx;
    color: #8f8f94;
}
</style>
