<template>
    <z-paging
        ref="paging"
        v-model="dataList"
        auto-show-back-to-top
        :auto="false"
        :data-key="swiperIndex"
        :fixed="false"
        height="100%"
        @query="queryArticleList"
    >
        <view class="layout-article-widget">
            <view v-for="(item, index) in dataList" :key="index" class="item" @click="$go('/pages/article/detail?id='+item.id)">
                <u-image
                    :lazy-load="true"
                    width="240rpx"
                    height="180rpx"
                    border-radius="4"
                    :src="item.image"
                    style="flex-shrink: 0;"
                />
                <view class="flex flex-1 flex-col justify-between px-20">
                    <view class="truncate-line-1 font-xl font-weight-medium color-main ">{{ item.title }}</view>
                    <view class="truncate-line-2 font-xs color-text">{{ item.intro }}</view>
                    <view class="flex justify-between">
                        <view class="font-xs color-muted">{{ item.create_time }}</view>
                        <view class="font-xs color-muted">{{ item.browse }}人浏览</view>
                    </view>
                </view>
            </view>
        </view>
    </z-paging>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'
import articleApi from '@/api/articleApi'

const paging = ref(null)
const isFirst = ref(false)
const dataList = ref([])

const props = defineProps({
    cid: {
        type: Number,
        default: 0
    },
    keyword: {
        type: String,
        default: ''
    },
    tabIndex: {
        type: Number,
        default: 0
    },
    swiperIndex: {
        type: Number,
        default: 0
    }
})

watch(
    () => props.tabIndex,
    async (newVal) => {
        await nextTick()
        if (newVal === props.swiperIndex) {
            if (!isFirst.value) {
                uni.showLoading({title: '加载中...'})
                setTimeout(() => {
                    paging.value?.reload()
                }, 5)
            }
        }
    },
    { immediate: true }
)

watch(
    () => props.keyword,
    async () => {
        await nextTick()
        paging.value?.reload()
    }
)

const queryArticleList = async (pageNo) => {
    let params = {
        keyword: props.keyword,
        cid: props.cid,
        pageNo: pageNo
    }
    articleApi.lists(params).then(res => {
        paging.value.complete(res.data)
        isFirst.value = true
    }).catch(() => {
        paging.value.complete(false)
    })
}
</script>

<style lang="scss">
.layout-article-widget {
    margin-top: 20rpx;
    .item {
        display: flex;
        justify-content: space-between;
        padding: 20rpx;
        border-bottom: 1rpx dashed #f2f2f2;
        background-color: #ffffff;
    }
}
</style>
