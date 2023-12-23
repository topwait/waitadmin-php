<template>
    <view :class="themeName">
        <z-paging-swiper>
            <template #top>
                <ArticleSearchInput @search="onSearch" />
                <u-tabs-swiper
                    ref="uTabs"
                    inactive-color="#999999"
                    :active-color="themeColor"
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
    </view>
</template>

<script setup>
import { ref, getCurrentInstance } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import articleApi from '@/api/articleApi'
import ArticlePagInList from './component/article-pagin-list'
import ArticleSearchInput from './component/article-search-input'

// 参数定义
const keyword = ref('')
const tabList = ref([])
const current = ref(0)
const swiperCurrent = ref(0)
const currentInstance = getCurrentInstance()

// 发起搜索
const onSearch = (e) => {
    keyword.value = e.keyword
}

// 切换页面
const tabChange = (e) => {
    swiperCurrent.value = e
}

// 切换过度
const transition = (e) => {
    let dx = e.detail.dx
    currentInstance.ctx.$refs.uTabs.setDx(dx)
}

// 切换动画
const animations = (e) => {
    let index = e.detail.current
    currentInstance.ctx.$refs.uTabs.setFinishCurrent(index)
    swiperCurrent.value = index
    current.value = index
}

onLoad(async () => {
    tabList.value = await articleApi.category()
})
</script>
