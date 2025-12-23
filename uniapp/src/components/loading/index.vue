<template>
    <view v-if="visible" class="screen-loading" :style="{backgroundColor}">
        <wd-loading
            :type="type"
            :color="color"
            :size="size"
        />
    </view>
</template>

<script setup lang="ts">
const props = defineProps({
    // 指示器显示
    show: {
        type: Boolean,
        default: true
    },
    // 指示器类型
    type: {
        type: String as PropType<'ring' | 'outline'>,
        default: 'ring'
    },
    // 指示器大小
    size: {
        type: [String, Number],
        default: 32
    },
    // 指示器颜色
    color: {
        type: String,
        default: '#4d80f0'
    },
    // 延迟关闭 (毫秒)
    delay: {
        type: Number,
        default: 0
    },
    // 背景颜色
    backgroundColor: {
        type: String,
        default: 'var(--bg-color-page)'
    }
})

const visible = ref<boolean>(true)
watch(
    () => props.show,
    (val: boolean) => {
        if (!val && props.delay) {
            setTimeout(() => {
                visible.value = false
            }, props.delay)
        } else {
            visible.value = val
        }
    }, { immediate: true }
)
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
