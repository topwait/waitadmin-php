<template>
    <z-paging
        ref="paging"
        v-model="dataList"
        :data-key="swiperIndex"
        :fixed="false"
        :auto="swiperIndex === tabIndex"
        @query="queryList"
    >
        <view class="layout-article-widget">
            <view v-for="(item, i) in dataList" :key="i" class="item">
                <view class="flex justify-between">
                    <u-image :lazy-load="true" width="240rpx" height="180rpx" :src="item.image" style="flex-shrink: 0;" />
                    <view class="flex flex-col justify-between ml-20">
                        <view class="title text-xl color-main font-medium">{{ item.title }}</view>
                        <view class="intro text-xs color-text">{{ item.intro }}</view>
                        <view class="flex justify-between">
                            <view class="text-xs color-muted">2022-09-30 11:32:01</view>
                            <view class="text-xs color-muted">45人浏览</view>
                        </view>
                    </view>
                </view>
            </view>
        </view>
    </z-paging>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'
import { getArticleApi } from '@/api/articleApi'

const paging = ref(null)
const isFirst = ref(true)
const dataList = ref([])

const props = defineProps({
    cid: {
        type: Number,
        default: () => 0
    },
    tabIndex: {
        type: Number,
        default: () => 0
    },
    swiperIndex: {
        type: Number,
        default: () => 0
    }
})

watch(
    () => props.tabIndex,
    async () => {
        await nextTick()
        if (props.tabIndex === props.swiperIndex && isFirst.value) {
            isFirst.value = false
            paging.value?.reload()
        }
    },
    { immediate: true }
)

const queryList = async (pageNo, pageSize) => {
    getArticleApi({
        cid: props.cid,
        pageNo,
        pageSize
    }).then(res => {
        paging.value.complete(res.data.list)
    }).catch(() => {
        paging.value.complete(false)
    })
}
</script>

<style lang="scss">
.layout-article-widget {
    margin-top: 20rpx;
    .item {
        padding: 20rpx;
        border-bottom: 1rpx dashed #f2f2f2;
        background-color: #ffffff;
        .title {
            display: -webkit-box;
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-line-clamp: 2;
            word-break: break-all;
            -webkit-box-orient: vertical;
        }
        .intro {
            display: -webkit-box;
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-line-clamp: 1;
            word-break: break-all;
            -webkit-box-orient: vertical;
        }
    }
}
</style>
