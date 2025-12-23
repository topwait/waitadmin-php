
<template>
    <z-paging-swiper>
        <template #top>
            <view class="border-b border-br-thinned">
                <paging-search />
            </view>
            <wd-tabs
                v-model="tabIndex"
                slidable="always"
                color="var(--color-primary)"
                inactive-color="var(--text-color-regular)"
                @change="changeTab"
            >
                <wd-tab
                    v-for="item in tabList"
                    :key="item.id"
                    :title="item.name"
                />
            </wd-tabs>
        </template>
        <swiper
            :current="swiperIndex"
            :style="{ height: '100%' }"
            @animationfinish="animations"
        >
            <swiper-item v-for="(item, index) in tabList" :key="index">
                <paging-list
                    :cid="item.id"
                    :keyword="keyword"
                    :tab-index="tabIndex"
                    :swiper-index="index"
                />
            </swiper-item>
        </swiper>
    </z-paging-swiper>
</template>

<script setup lang="ts">
import articleApi from '@/api/article'
import PagingList from './_components/paging-list.vue'
import PagingSearch from './_components/paging-search.vue'

const tabList = ref<any>([])
const keyword = ref<string>('')
const tabIndex = ref<number>(0)
const swiperIndex = ref<number>(0)

/**
 * 切换选项
 *
 * @param {number} index
 * @author zero
 */
const changeTab = ({ index }: { index: number }) => {
    swiperIndex.value = index
}

/**
 * 切换动画
 *
 * @param {any} e
 * @author zero
 */
const animations = (e: any) => {
    const index = e.detail.current
    swiperIndex.value = index
    tabIndex.value = index
}

/**
 * 页面显示
 */
onShow(async () => {
    tabList.value = await articleApi.category()
})
</script>
