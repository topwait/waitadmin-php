<template>
    <view class="bg-overlay" :style="containerStyle">
        <!-- 标题区-->
        <view
            v-if="title || moreShow"
            class="flex items-center justify-between p-3 pb-1"
            :class="{'pb-3 border-b border-br-thinned': props.titleLine}"
        >
            <view v-if="title" class="font-semibold text-tx-primary" :style="{ fontSize: titleSize }">
                {{ title }}
            </view>
            <view v-if="moreShow" class="flex items-center text-thinned">
                <text v-if="moreName" :style="{ fontSize: moreNameSize }">
                    {{ moreName }}
                </text>
                <wd-icon v-if="moreIconSize" name="arrow-right" :size="moreIconSize" />
            </view>
        </view>

        <!-- 行模式 -->
        <view v-if="mode === 'row'" class="flex flex-wrap gap-y-5 py-3.5">
            <button
                v-for="(item, index) in list"
                :key="index"
                hover-class="none"
                class="relative flex flex-col justify-center items-center gap-1.5"
                :style="{ width: gridWidth }"
                @click="$go(item.link)"
            >
                <wd-img
                    :width="imageSize||'60rpx'"
                    :height="imageSize||'60rpx'"
                    :src="item.image"
                />
                <wd-text
                    :text="item.name"
                    :size="textSize||'26rpx'"
                    line-height="normal"
                    class="align-middle"
                />
                <view
                    v-if="badgeShow"
                    class="absolute"
                    :style="{
                        top: `${badgeTop||-18}rpx`,
                        left: `${badgeLeft||110}rpx`
                    }"
                >
                    <wd-badge
                        :hidden="isDot && !item.value"
                        :model-value="parseInt(String(item.value||'0'))"
                        :max="99"
                        :type="badgeType"
                        :is-dot="isDot"
                        :show-zero="badgeZero"
                        :bg-color="badgeBg"
                    />
                </view>
            </button>
        </view>

        <!-- 列模式 -->
        <view v-else-if="mode === 'col'">
            <button
                v-for="(item, index) in list"
                :key="index"
                class="flex items-center gap-2 p-3 border-b last:border-b-0 border-br-thinned"
                hover-class="none"
                @click="$go(item.link)"
            >
                <view class="relative flex-1 flex items-center gap-2">
                    <wd-img
                        :width="imageSize||'48rpx'"
                        :height="imageSize||'48rpx'"
                        :src="item.image"
                    />
                    <wd-text
                        :text="item.name"
                        :size="textSize||'28rpx'"
                        line-height="normal"
                        class="flex-1 text-left align-middle"
                    />
                    <view
                        v-if="badgeShow"
                        class="absolute"
                        :style="{
                            top: `${badgeTop||-18}rpx`,
                            left: `${badgeLeft||10}rpx`
                        }"
                    >
                        <wd-badge
                            :hidden="isDot && !item.value"
                            :model-value="parseInt(String(item.value||'0'))"
                            :max="99"
                            :type="badgeType"
                            :is-dot="isDot"
                            :show-zero="badgeZero"
                            :bg-color="badgeBg"
                        />
                    </view>
                </view>
                <view class="text-tx-placeholder">
                    <wd-icon :name="arrowIcon" :size="arrowSize" />
                </view>
            </button>
        </view>
    </view>
</template>

<script setup lang="ts">
interface ListType {
    name: string;
    link: string;
    image?: string;
    value?: number|string;
}

const props = defineProps({
    // 数据列表
    list: {
        type: Array<ListType>,
        default: () => []
    },
    // 渲染模式
    mode: {
        type: String as PropType<'row' | 'col'>,
        default: () => 'row'
    },
    // 渲染网格
    grid: {
        type: Number as PropType<1 | 2 | 3 | 4 | 5>,
        default: () => 4
    },
    // 圆角大小
    rounded: {
        type: String,
        default: '24rpx'
    },
    // 左右边距
    marginX: {
        type: String,
        default: () => '24rpx'
    },
    // 上外边距
    marginT: {
        type: String,
        default: () => '24rpx'
    },
    // 下外边距
    marginB: {
        type: String,
        default: () => '0'
    },
    // 标题名称
    title: {
        type: String,
        default: () => null
    },
    // 标题大小
    titleSize: {
        type: String,
        default: () => '32rpx'
    },
    // 标题下线
    titleLine: {
        type: Boolean,
        default: () => false
    },
    // 更多显示
    moreShow: {
        type: Boolean,
        default: () => false
    },
    // 更多文案
    moreName: {
        type: String,
        default: () => ''
    },
    // 更多文字大小
    moreNameSize: {
        type: String,
        default: () => '26rpx'
    },
    // 更多图标大小
    moreIconSize: {
        type: String,
        default: () => '32rpx'
    },
    // 文字大小
    textSize: {
        type: String,
        default: () => null
    },
    // 图片尺寸
    imageSize: {
        type: String,
        default: () => null
    },
    // 箭头图标
    arrowIcon: {
        type: String,
        default: () => 'arrow-right'
    },
    // 箭头大小
    arrowSize: {
        type: String,
        default: () => '32rpx'
    },
    // 点状标注
    isDot: {
        type: Boolean,
        default: () => false
    },
    // 徽章显示
    badgeShow: {
        type: Boolean,
        default: () => true
    },
    // 徽章顶距
    badgeTop: {
        type: Number,
        default: () => null
    },
    // 徽章左距
    badgeLeft: {
        type: Number,
        default: () => null
    },
    // 徽章背景
    badgeBg: {
        type: String,
        default: () => ''
    },
    // 徽章0显示
    badgeZero: {
        type: Boolean,
        default: () => false
    },
    // 徽章类型
    badgeType: {
        type: String as PropType<'primary' | 'success' | 'warning' | 'danger' | 'info'>,
        default: () => 'danger'
    }
})

const gridWidth = computed(() => `${100 / props.grid}%`)

const containerStyle = computed(() => ({
    margin: `0 ${props.marginX}`,
    marginTop: props.marginT,
    marginBottom: props.marginB,
    borderRadius: props.rounded
}))
</script>
