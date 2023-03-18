<template>
    <z-paging-swiper>
        <template #top>
            <ArticleSearchInput @search="onSearch" />
            <u-tabs-swiper
                ref="uTabs"
                inactive-color="#999999"
                :list="tabList"
                :current="current"
                @change="tabChange"
            />
        </template>
        <swiper :current="swiperCurrent" style="height: 100%;" @transition="transition" @animationfinish="animations">
            <swiper-item v-for="(item, index) in tabList" :key="index">
                <ArticlePagInList 
                    :cid="item.id"
                    :keyword="keyword"
                    :tab-index="current" 
                    :swiper-index="index" 
                />
            </swiper-item>
        </swiper>
    </z-paging-swiper>
</template>

<script setup>
import { ref, getCurrentInstance } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import { getArticleCateApi } from '@/api/articleApi'
import ArticlePagInList from './component/article-pagin-list.vue'
import ArticleSearchInput from './component/article-search-input'

const tabList = ref([])
const current = ref(0)
const swiperCurrent = ref(0)
const currentInstance = getCurrentInstance()
const keyword = ref('')

onLoad(() => {
    queryCategory()
})

const onSearch = (e) => {
    keyword.value = e.keyword
}

const tabChange = (e) => {
    swiperCurrent.value = e
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
    tabList.value = await getArticleCateApi()
}
</script>
