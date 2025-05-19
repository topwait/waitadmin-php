<template>
    <view class="layout-input-widget">
        <u-search
            placeholder="请输入搜索关键词"
            :show-action="false"
            @search="onInputBlur"
        />
    </view>
</template>

<script setup>
const emit = defineEmits(['search'])

const onInputBlur = (val) => {
    emit('search', {keyword: val})
}
</script>

<style lang="scss" scoped>
.layout-input-widget {
    padding: 12rpx 20rpx;
    border-bottom: 1px solid #f2f2f2;
    background-color: #ffffff;
}
</style>
