<template>
    <wd-search
        v-model="keyword"
        placeholder-left
        hide-cancel
        placeholder="请输入搜索关键词"
        custom-class="!px-2.5"
        custom-input-class="!h-[68rpx]"
        @search="onInputBlur"
    />
</template>

<script setup lang="ts">
const emits = defineEmits(['search'])

const keyword = ref<string>('')

const onInputBlur = (val: string) => {
    emits('search', {
        keyword: val
    })
}
</script>
