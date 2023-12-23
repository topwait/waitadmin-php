<template>
    <view class="screen-loading" :style="{backgroundColor}">
        <u-loading
            :mode="mode"
            :size="size"
            :color="color ? color : themeColor"
        />
    </view>
</template>

<script setup>
defineProps({
    backgroundColor: {
        type: String,
        default: '#ffffff'
    },
    mode: {
        type: String,
        default: 'circle'
    },
    size: {
        type: Number,
        default: 60
    },
    color: {
        type: String,
        default: ''
    }
})
</script>

<style lang="scss" scoped>
.screen-loading {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100vw;
    height: 100vh;
}
</style>
