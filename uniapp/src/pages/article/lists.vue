<template>
    <z-paging-swiper>
        <template #top>
            <u-tabs-swiper
                ref="uTabs"
                inactive-color="#999999"
                :list="tabList"
                :current="current"
                @change="tabChange"
            />
        </template>
        <swiper :current="swiperCurrent" @transition="transition" @animationfinish="animations" style="height: 100%;">
            <swiper-item v-for="(item, i) in tabList" :key="i">
                <ArticlePagInList :cid="item.id" :tabIndex="current" :swiperIndex="i" />
            </swiper-item>
        </swiper>
    </z-paging-swiper>
</template>

<script setup>
import { ref, watch, nextTick, getCurrentInstance } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import { getCategoryApi } from '@/api/articleApi'
import ArticlePagInList from './component/article-pagin-list.vue'

const tabList = ref([])
const current = ref(0)
const swiperCurrent = ref(0)
const currentInstance = getCurrentInstance()

onLoad(() => {
    queryCategory()
})

const tabChange = (e) => {
    current.value = e
}

const transition = (e) => {
    let dx = e.detail.dx
    currentInstance.ctx.$refs.uTabs.setDx(dx)
}

const animations = (e) => {
    let index = e.detail.current
    currentInstance.ctx.$refs.uTabs.setFinishCurrent(index)
    swiperCurrent.value = index
    current.value = index
}

const queryCategory = async () => {
    const { data } = await getCategoryApi()
    tabList.value = data
}
</script>
