<template>
    <z-paging-swiper>
        <template #top>
            <u-tabs-swiper
                ref="uTabs"
                inactive-color="#999999"
                :list="tabs"
                :current="current"
                @change="tabChange"
            />
        </template>
        <swiper class="swiper" :current="swiperCurrent" @transition="transition" @animationfinish="animationfinish">
            <swiper-item v-for="(item, i) in tabs" :key="i">
                <slot name="content" :cur="i"></slot>
            </swiper-item>
        </swiper>
    </z-paging-swiper>
</template>

<script setup>
import { getCurrentInstance, ref, defineEmits } from 'vue'

const props = defineProps({
    tabs: {
        type: Array,
        default: () => { return [] }
    }
})

const emit = defineEmits(['change'])
const currentInstance = getCurrentInstance()
const swiperCurrent = ref(0)
const current = ref(0)

const tabChange = (index) => {
    swiperCurrent.value = index
    emit('change', {id: props.tabs[index].id, index: index})
}

const transition = (e) => {
    let dx = e.detail.dx
    currentInstance.ctx.$refs.uTabs.setDx(dx)
}

const animationfinish = (e) => {
    let index = e.detail.current
    emit('change', {id: props.tabs[index].id, index: index})
    currentInstance.ctx.$refs.uTabs.setFinishCurrent(index)
    swiperCurrent.value = index
    current.value = index
}
</script>

<style lang="scss">
.swiper {
    height: 100%;
}
</style>
