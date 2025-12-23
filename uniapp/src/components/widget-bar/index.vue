<template>
    <!-- #ifndef MP-ALIPAY -->
    <view
        :style="{
            height: `${totalHeight}px`,
            backgroundColor: backgroundColor
        }"
    >
        <view :style="{ height: `${statusBarHeight}px`}" />

        <!-- #ifdef MP-WEIXIN -->
        <view :style="{ display: 'flex', height: `${menuHeight}px` }">
            <view class="flex-1 overflow-hidden">
                <slot />
            </view>
            <view :style="{ width: `${menuWidth}px`, height: `${menuHeight}px` }" />
        </view>
        <!--  #endif -->

    </view>
    <!-- #endif -->
</template>

<script setup lang="ts">
defineProps({
    // 背景颜色
    backgroundColor: {
        type: String,
        default: 'transparent'
    }
})

// 支付宝小程不支持自定义导航栏
// #ifndef MP-ALIPAY

// 设备的信息
const systemInfo = uni.getWindowInfo()

// 状态栏高度
const statusBarHeight = systemInfo.statusBarHeight || 0

// 胶囊的宽高
const menuWidth = shallowRef(0)
const menuHeight = shallowRef(0)

// 胶囊的信息
// #ifdef MP-WEIXIN
const menuButtonObject = uni.getMenuButtonBoundingClientRect()
menuWidth.value = systemInfo.windowWidth - menuButtonObject.right + menuButtonObject.width
menuHeight.value =  menuButtonObject.height + (menuButtonObject.top - statusBarHeight) * 2
// #endif

// 总高度 = 状态栏高度 + 胶囊高度 + (胶囊距离-胶囊高度)*2
const totalHeight = statusBarHeight + menuHeight.value

// #endif
</script>
