<template>
    <block v-if="mod === 'normal'">
        <view :class="[pt?('pt-'+pt):'', pb?('pb-'+pb):'']">
            <button
                class="button"
                :class="[bgColor?bgColor:'']"
                @click="onClick"
            >
                <slot></slot>
            </button>
        </view>
       
    </block>

    <block v-if="mod === 'together'">
        <view class="flex" :class="[pt?('pt-'+mt):'', pb?('pb-'+mb):'']">
            <button class="button rounded-tr-0 rounded-br-0">
                <slot name="left"></slot>
            </button>
            <button class="button rounded-tl-0 rounded-bl-0">
                <slot name="right"></slot>
            </button>
        </view>
    </block>
</template>

<script setup>
import { defineEmits } from 'vue'

defineProps({
    // 渲染模式: [normal/together/stand]
    mod: {
        type: String,
        default: () => 'normal'
    },
    // 顶部边距
    pt: {
        type: String,
        default: () => null
    },
    // 底部边距
    pb: {
        type: String,
        default: () => null
    },
    // 背景颜色
    bgColor: {
        type: String,
        default: () => null
    }
})

const emit = defineEmits(['on-click'])
const onClick = () => {
    emit('on-click')
}
</script>

<style lang="scss">
.button {
    padding: 2rpx 0;
    width: 100%;
    font-size: 32rpx;
    border-radius: 50rpx;
    text-align: center;
    color: #ffffff;
    line-height: 2.3;
    background-color: $uni-bg-theme;
}
</style>
