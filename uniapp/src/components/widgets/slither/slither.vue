<template>
    <z-paging-swiper>
        <template #top>
            <u-tabs-swiper
                ref="uTabs"
                inactive-color="#999999"
                :list="list"
                :current="current"
                @change="tabChange"
            />
        </template>
        <swiper class="swiper" :current="swiperCurrent" @transition="transition" @animationfinish="animationfinish">
            <swiper-item v-for="(item, index) in list" :key="index">
                <slot name="content"></slot>
            </swiper-item>
        </swiper>
    </z-paging-swiper>
</template>

<script setup>
import { getCurrentInstance, ref } from 'vue'

defineProps({
    list: {
        type: Array,
        default: () => { return [] }
    },
    key: {
        type: Number,
        default: () => 0
    }
})

const currentInstance = getCurrentInstance()
const swiperCurrent = ref(0)
const current = ref(0)

const tabChange = (index) => {
    swiperCurrent.value = index
}

const transition = (e) => {
    let dx = e.detail.dx
    currentInstance.ctx.$refs.uTabs.setDx(dx)
}

const animationfinish = (e) => {
    let index = e.detail.current
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
