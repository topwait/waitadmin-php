<template>
    <block v-if="mod === 'normal'">
        <view :class="[pt?('pt-'+pt):'', pb?('pb-'+pb):'']">
            <button
                class="button"
                :class="[bgColor?bgColor:'', load?'load':'']"
                @tap="$u.debounce(onClick, 200)"
            >   
                <u-loading mode="flower" :show="loading" /> <slot></slot>
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
import { ref, watch } from 'vue'
import { defineEmits } from 'vue'

const emit = defineEmits(['on-click'])
const loading = ref(false)

const props = defineProps({
    // 渲染模式: [normal/together/stand]
    mod: {
        type: String,
        default: 'normal'
    },
    // 是否加载
    load: {
        type: Boolean,
        default: false
    },
    // 顶部边距
    pt: {
        type: String,
        default: null
    },
    // 底部边距
    pb: {
        type: String,
        default: null
    },
    // 背景颜色
    bgColor: {
        type: String,
        default: null
    }
})

// 监听属性
watch(() => props.load,
    (val) => {
        loading.value = val
    }, { immediate: true }
)

// 点击事件
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
    &.load {
        background-color: #5896ff;
    }
}
</style>
