<template>

    <u-tabs
        :list="tabList"
        :current="current"
        inactive-color="#999999"
        @change="tabChange"
    />
    
    <z-paging ref="paging" v-model="dataList" @query="queryList"  auto-show-back-to-top>
        <view class="layout-article-widget">
            <view class="item">
                <view class="flex justify-between">
                    <u-image :lazy-load="true" width="240rpx" height="180rpx" :src="'https://php.likeadmin.cn/uploads/images/20220930/20220930113600b5f353187.jpeg'" style="flex-shrink: 0;"></u-image>
                    <view class="flex flex-col justify-between ml-20">
                        <view class="title text-xl color-main font-medium">沐浴球的正确使用方法沐浴球的正确使用方法</view>
                        <view class="intro text-xs color-text">沐浴球是使沐浴露起泡的工具,使用的和恢复那你看见本报发稿</view>
                        <view class="flex justify-between">
                            <view class="text-xs color-muted">2022-09-30 11:32:01</view>
                            <view class="text-xs color-muted">45人浏览</view>
                        </view>
                    </view>
                </view>
            </view>
            <view class="item">
                <view class="flex justify-between">
                    <u-image :lazy-load="true" width="240rpx" height="180rpx" :src="'https://php.likeadmin.cn/uploads/images/20220930/20220930113600b5f353187.jpeg'" style="flex-shrink: 0;"></u-image>
                    <view class="flex flex-col justify-between ml-20">
                        <view class="title text-xl color-main font-medium">沐浴球的正确使用方法</view>
                        <view class="intro text-xs color-text">沐浴球是使沐浴露起泡的工具,使用的和恢复那你看见本报发稿</view>
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
import { ref, shallowRef } from 'vue'


const paging = shallowRef(null)
const dataList = ref([])
const current = ref(0)
const tabList = [
    {'name': '全部'},
    {'name': '社会资讯'}
];


const tabChange = (index) => {
    current.value = index
}

const queryList = (pageNo, pageSize) => {
    console.log('aaaa')
    paging.value.complete([{'id': 1, 'title': '哈哈哈哈'}]);
}

</script>

<style lang="scss">
.layout-article-widget {
    margin-top: 20rpx;
    .item {
        padding: 20rpx;
        background-color: #ffffff;
        border-bottom: 1rpx dashed #f2f2f2;
        .title {
            -webkit-line-clamp: 2;
            overflow: hidden;
            word-break: break-all;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
        }
        .intro {
            -webkit-line-clamp: 1;
            overflow: hidden;
            word-break: break-all;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
        }
    }
}
</style>
