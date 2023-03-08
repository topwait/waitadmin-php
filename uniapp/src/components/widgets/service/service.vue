<template>
    <view v-if="list" class="layout-service-widget">
        <view v-if="title" class="service-header">
            <view>{{ title }}</view>
            <view v-if="more">
                <text class="text-sm font-normal color-text">{{ moreName }}</text>
                <u-icon name="arrow-right" color="#666666" size="26" />
            </view>
        </view>

        <view v-if="mod === 'row'" class="service-mould">
            <button
                v-for="(item, index) in list"
                :key="index"
                class="service-apply-item"
                :style="{ width: grid }"
                hover-class="none"
                @tap="$go(item.link)"
            >
                <image :class="'mc-0 w-h-'+(iconSize||'60')" :src="item.image" />
                <view class="mt-10 leading-tight text-xs color-main" :style="textStyle">{{ item.name }}</view>
            </button>
        </view>

        <view v-if="mod === 'col'" class="service-lists">
            <button
                v-for="(item, index) in list"
                :key="index"
                class="service-apply-item"
                hover-class="none"
                @tap="$go(item.link)"
            >
                <image :class="'w-h-'+(iconSize||'48')" :src="item.image" />
                <view class="flex-1 ml-20 text-left color-main" :style="textStyle">{{ item.name }}</view>
                <u-icon name="arrow-right" color="#666666" size="16" />
            </button>
        </view>
    </view>
</template>

<script setup>
defineProps({
    // 渲染模式: [row/col]
    mod: {
        type: String,
        default: () => 'row'
    },
    // 渲染网格: [20%/25%]
    grid: {
        type: String,
        default: () => '25%'
    },
    // 渲染更多
    more: {
        type: Boolean,
        default: () => false
    },
    // 渲染标题
    title: {
        type: String,
        default: () => null
    },
    // 更多文案
    moreName: {
        type: String,
        default: () => ''
    },
    // 图标大小
    iconSize: {
        type: String,
        default: () => null
    },
    // 文字样式
    textStyle: {
        type: Object,
        default: () => ({})
    },
    // 列表数据
    list: {
        type: Array,
        default: () => []
    }
})
</script>

<style lang="scss">
.layout-service-widget {
    margin: 20rpx;
    border-radius: 14rpx;
    background-color: #ffffff;
    .service-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 10px 5px;
        font-size: 30rpx;
        font-weight: 600;
        color: #282828;
    }
    .service-mould {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        padding: 14rpx 0;
        .service-apply-item {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin: 18rpx 0;
            width: 25%;
            text-align: center;
        }
    }
    .service-lists {
        display: flex;
        flex-direction: column;
        .service-apply-item {
            display: flex;
            align-items: center;
            padding: 0 20rpx;
            height: 100rpx;
            border-bottom: 1px solid #f6f6f6;
        }
    }
}
</style>
