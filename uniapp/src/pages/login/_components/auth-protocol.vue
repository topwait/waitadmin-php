<template>
    <view class="flex items-center text-xs text-tx-placeholder">
        <wd-checkbox v-model="internalChecked" custom-label-class="-mr-1" />
        <text>已阅读并同意</text>
        <text
            class="text-tx-secondary"
            @click="$go('/bundle/pages/index/policy?type=privacy')"
        >
            《隐私协议》
        </text>
        <text>与</text>
        <text
            class="text-tx-secondary"
            @click="$go('/bundle/pages/index/policy?type=service')"
        >
            《用户协议》
        </text>
    </view>
</template>

<script setup lang="ts">
const props = defineProps<{
    modelValue: boolean
}>()

const emits = defineEmits<{
    (_e: 'update:modelValue', _value: boolean): void
}>()

const internalChecked = computed({
    get: () => props.modelValue,
    set: (val: boolean) => emits('update:modelValue', val)
})
</script>
