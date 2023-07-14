<template>
    <block v-if="mod === 'together'">
        <view
            class="flex"
            :class="[
                pt?('pt-'+pt):'',
                pb?('pb-'+pb):'',
                pl?('pl-'+pl):'',
                pr?('pb-'+pr):''
            ]"
        >
            <u-button
                :type="btn1Type"
                shape="circle"
                class="w-h-full rounded-tr-0 rounded-br-0"
                @tap="trigger"
            >
                <slot name="left"></slot>
            </u-button>
            <u-button
                :type="btn2Type"
                shape="circle"
                class="w-h-full rounded-tl-0 rounded-bl-0"
                @tap="trigger"
            >
                <slot name="right"></slot>
            </u-button>
        </view>
    </block>
</template>

<script setup>
defineProps({
    // 渲染模式: [normal/together]
    mod: {
        type: String,
        default: 'together'
    },
    // 顶内边距:  [0-100]
    pt: {
        type: String,
        default: null
    },
    // 底内边距:  [0-100]
    pb: {
        type: String,
        default: null
    },
    // 左内边距:  [0-100]
    pl: {
        type: String,
        default: null
    },
    // 右内边距:  [0-100]
    pr: {
        type: String,
        default: null
    },
    // 按钮1类型
    btn1Type: {
        type: String,
        default: 'warning'
    },
    // 按钮2类型
    btn2Type: {
        type: String,
        default: 'error'
    }
})
</script>
